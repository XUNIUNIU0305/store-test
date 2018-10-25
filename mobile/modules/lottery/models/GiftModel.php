<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-12
 * Time: 下午2:15
 */

namespace mobile\modules\lottery\models;

use common\models\Model;
use common\ActiveRecord\LotteryChanceItemAR;
use mobile\modules\lottery\models\lottery\ChanceItem;
use yii\web\BadRequestHttpException;

class GiftModel extends Model
{
    const SCE_VIEW = 'view';
    const SCE_NOTIFY = 'notify';

    /**
     * 抽奖机会ID
     * @var int
     */
    public $item_id;

    public function scenarios()
    {
        return [
            self::SCE_VIEW => [
                'item_id'
            ],
            self::SCE_NOTIFY => []
        ];
    }

    public function rules()
    {
        return [
            [
                ['item_id'],
                'integer',
                'min' => 1,
                'message' => 9002
            ],
            [
                ['item_id'],
                'required',
                'message' => 9001
            ]
        ];
    }

    /**
     * 抽奖结果
     * @return bool|array
     */
    public function view()
    {
        try{
            $item = ChanceItem::getInstanceById($this->item_id);
            $chance = $item->getChance();
            if($item->status !== LotteryChanceItemAR::STATUS_OPENED || $chance->custom_user_id !== \Yii::$app->user->id){
                throw new BadRequestHttpException('not found');
            }
            return [
                'result' => $item->result,
                'account' => $chance->account,
                'prize' => ($prize = $item->getPrize()) ? $prize->getAttributes([
                    'id', 'name', 'type'
                ]) : []
            ];
        } catch (\Exception $e){
            $this->addError('item_id', 9002);
            return false;
        }
    }

    public function notify()
    {
        try{
            return ChanceItem::queryNotify();
        } catch (\Exception $e){
            $this->addError('notify', 9002);
            return false;
        }
    }
}