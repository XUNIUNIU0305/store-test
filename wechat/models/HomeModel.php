<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/17
 * Time: 17:08
 */

namespace wechat\models;


use common\components\handler\mobile\MobileAdvertiseHandler;
use common\models\Model;
use common\models\parts\mobile\MobileAdvertise;
use Yii;

class HomeModel extends  Model
{

    const SCE_GET_ADV_LIST="get_home_adv";
    const SCE_GET_USER_STATUS="get_user_status";
    const SCE_GET_BALANCE = 'get_balance';


    public $page_size;
    public $current_page;
    public function scenarios()
    {
        return [
            self::SCE_GET_ADV_LIST=>['page_size','current_page'],
            self::SCE_GET_USER_STATUS=>[],
            self::SCE_GET_BALANCE => []
        ];
    }

    public function rules()
    {
        return [
            [
                ['page_size'],
                'default',
                'value'=>10,
            ],
            [
                ['current_page'],
                'default',
                'value'=>1,
            ],
            [
                ['page_size','current_page'],
                'required',
                'message'=>9001,
            ],

        ];
    }

    /**
     * 获取帐号余额
     * @return bool
     */
    public function getBalance()
    {
        try{
            $rmb = Yii::$app->CustomUser->wallet->rmb;
            return compact('rmb');
        } catch (\Exception $e){
            $this->addError('', 11002);
            return false;
        }
    }

    //检测获取用户状态
    public function getUserStatus(){

        if(!Yii::$app->user->isGuest){
            return ['status'=>1];
        }else{
            if(!Yii::$app->session->get(Yii::$app->params['LOGIN_KEY'],false)){
                return ['status'=>0];
            }
        }

        return ['status'=>-1];
    }


    /**
     * Author:JiangYi
     * Date:2017/05/19
     * Desc:获取首页广告位图片
     * @return array
     */
    public function getHomeAdv(){
        $model=MobileAdvertiseHandler::search($this->page_size,$this->current_page,MobileAdvertise::STATUS_NORMAL,MobileAdvertise::TYPE_HOME);
        $data=array_map(function($item){
            $advertise=new MobileAdvertise(['id'=>$item['id']]);
            return [
                'id'=>$advertise->id,
                'path'=>$advertise->path,
                'url'=>$advertise->url,
                'sort'=>$advertise->sort,
                'status'=>$advertise->status,
            ];
        },$model->models);
        return [
            'count' => $model->count,
            'total_count' => $model->totalCount,
            'codes' => $data,
        ];
    }
}