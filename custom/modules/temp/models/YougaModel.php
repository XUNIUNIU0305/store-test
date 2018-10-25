<?php
namespace custom\modules\temp\models;

use Yii;
use common\models\Model;
use common\ActiveRecord\TempYougaZodiacAR;
use common\ActiveRecord\TempYougaZodiacNumberAR;
use custom\modules\temp\models\parts\Zodiac;
use custom\components\handler\TradeHandler;
use custom\models\parts\trade\PaymentMethod;
use yii\helpers\Url;
use custom\models\parts\UrlParamCrypt;

class YougaModel extends Model{

    const SCE_GET_NUMBER = 'get_number';
    const SCE_ORDER_NUMBER = 'order_number';
    const SCE_GET_SELECTED_NUMBER = 'get_selected_number';

    public $zodiac;
    public $numbers_id;

    public function scenarios(){
        return [
            self::SCE_GET_NUMBER => [
                'zodiac',
            ],
            self::SCE_ORDER_NUMBER => [
                'numbers_id',
            ],
            self::SCE_GET_SELECTED_NUMBER => [
                'zodiac',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['zodiac', 'numbers_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['zodiac'],
                'exist',
                'targetClass' => TempYougaZodiacAR::className(),
                'targetAttribute' => 'id',
                'message' => 3221,
            ],
        ];
    }

    public function getSelectedNumber(){
        return Yii::$app->RQ->AR(new TempYougaZodiacNumberAR)->all([
            'select' => ['id', 'num'],
            'where' => [
                'custom_user_id' => Yii::$app->user->id,
                'temp_youga_zodiac_id' => $this->zodiac,
                'selected' => Zodiac::STATUS_SELECTED,
            ],
        ]);
    }

    public function orderNumber(){
        if(strtotime('2017-03-06 23:00:00') < Yii::$app->time->unixTime){
            $this->addError('orderNumber', 3241);
            return false;
        }
        try{
            $zodiac = new Zodiac([
                'selectedNumber' => $this->numbers_id,
            ]);
        }catch(\Exception $e){
            $this->addError('orderNumber', 3231);
            return false;
        }
        if(Yii::$app->CustomUser->wallet->rmb < $zodiac->totalFee){
            $this->addError('orderNumber', 3232);
            return false;
        }
        $payment = new PaymentMethod([
            'method' => PaymentMethod::METHOD_BALANCE,
        ]);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $trade = TradeHandler::createZodiacTrade($zodiac, $payment);
            if(Yii::$app->CustomUser->wallet->pay($trade)){
                $q = (new UrlParamCrypt)->encrypt($trade->totalFee);
                $callback = ['url' => Url::to(['/trade/balance', 'q' => $q])];
            }else{
                throw new \Exception;
            }
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('orderNumber', 3233);
            return false;
        }
        return $callback;
    }

    public function getNumber(){
        return (new Zodiac(['id' => $this->zodiac]))->allNumber;
    }

    public static function getSelected(){
        return Zodiac::getList();
    }
}
