<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/7/26
 * Time: 下午3:04
 */

namespace mobile\modules\member\models;


use business\models\parts\Promoter;
use common\ActiveRecord\PartnerPromoterAR;
use common\components\handler\PromoterHandler;
use business\modules\site\models\PromoterModel;
use common\models\parts\partner\UrlParamCrypt;
use dosamigos\qrcode\QrCode;
use Yii;
use yii\web\Response;

class InviteModel extends PromoterModel
{
    const SCE_CODE_IMG = 'get_code_img';
    const SCE_CODE_INFO = 'get_code_info';
    const SCE_NUM = 'num';

    public function scenarios()
    {
        $scenario =  [
            self::SCE_CODE_IMG=>[],
            self::SCE_CODE_INFO=>[],
            self::SCE_NUM=>[],
        ];

        return array_merge(parent::scenarios(),$scenario);
    }

    public function rules()
    {
        return parent::rules();
    }

    /**
     *====================================================
     * 获取二维码图片
     * @return bool|void
     * @author shuang.li
     *====================================================
     */
    public function getCodeImg(){
        try {
            $id = null;
            $encryptModel = new UrlParamCrypt();
            $this->createCode($id);
            if(!$this->validate())  throw new \Exception('validate failed');
            $response = Yii::$app->response;
            $response->headers->set('Content-Type', 'image/png');
            $response->format = Response::FORMAT_RAW;
            return QrCode::png(Yii::$app->params['ADMIN_Hostname'].'/partner?q='.$encryptModel->encrypt($id),false,0,5,1);
        }catch (\Exception $exception){
            $this->addError('code',10021);
            return false;
        }
    }

    /**
     *====================================================
     * 邀请纪录
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function inviteLog(){
        $model = new Promoter(['id'=>$this->id]);
        $where = $this->searchCondition();
        if(!empty($this->search_text)){
            $where .= ' and p.mobile = '.$this->search_text .'  or r.account = '.$this->search_text;
        }
        $inviteLog = $model->getInviteLog($this->current_page,$this->page_size,$where,$this->status);
        return [
            'count'=>$inviteLog->count,
            'total_count'=>$inviteLog->totalCount,
            'data'=>$inviteLog->models,
        ];

    }


    /**
     *====================================================
     * 获取二维码信息
     * @return array|bool
     * @author shuang.li
     *====================================================
     */
    public function getCodeInfo(){
        try{
            $id = null;
            $this->createCode($id);
            $userInfo = PromoterHandler::getUserInfo();
            return [
                'id'=>$id,
                'address'=>$userInfo['nick_name'] ? :$userInfo['account'],
            ];
        }catch (\Exception $exception){
            $this->addError('address',10020);
            return false;
        }

    }

    /**
     *====================================================
     * 邀请数量
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function num(){
        return PromoterHandler::getStreamCount($this->getPromoterId());
    }

    /**
     *====================================================
     * 创建二维码
     * @param $id
     * @author shuang.li
     * @throws \Exception
     *====================================================
     */
    private function createCode(&$id){
        $data = [
            'type'=>2,//custom
            'custom_user_id'=>Yii::$app->user->id,
        ];
        if (!$id = Yii::$app->RQ->AR(new PartnerPromoterAR())->scalar(['select'=>['id'], 'where'=>$data,])){
            if(($id = PromoterHandler::create($data)) == false) throw new \Exception('create failed');
        }
    }

    /**
     *====================================================
     * 获取当前用户可利用的所有邀请码id
     * @author shuang.li
     * @return array
     *====================================================
     */
    private function getPromoterId(){
        return  Yii::$app->RQ->AR(new PartnerPromoterAR())->column([
            'select'=>['id'],
            'where'=>[
                'custom_user_id'=>Yii::$app->user->id,
                'is_available' => 1,
            ]
        ]);
    }


}