<?php
/**
 * User: JiangYi
 * Date: 2017/5/19
 * Time: 11:20
 * Desc:
 */

namespace mobile\modules\member\models;


use Yii;

class IndexModel extends \custom\models\IndexModel
{

    const SCE_GET_USER_INFO="get_user_info";//获取用户列表
    const SCE_GET_USER_BALANCE="get_user_balance";//获取用户账户余额


    public function scenarios()
    {
        return [
            self::SCE_GET_USER_INFO=>[],//获取用户基本信息
            self::SCE_GET_USER_BALANCE=>[],//获取用户账户余额
        ];
    }


    /**
     * Author:JiangYi
     * Date:2017/5/19
     * Desc:获取会员账户余额
     * @return array
     */
    public  function getUserBalance(){
        return [
            'rmb' => Yii::$app->CustomUser->wallet->rmb,
        ];
    }






}