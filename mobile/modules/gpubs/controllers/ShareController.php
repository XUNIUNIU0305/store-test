<?php
namespace mobile\modules\gpubs\controllers;

use Yii;
use common\controllers\Controller;
use mobile\modules\gpubs\models\ShareModel;

class ShareController extends Controller{

    public $access = [
        'index' => ['@', 'get'],
        'inviting-friends' => ['@', 'get'],
        'member-info' => ['@', 'get'],
        'info' => ['@', 'get'],//团员拼购信息
        'detail' => ['@', 'get'],//拼购详情
        'wx-info' => ['@', 'get'],//微信分享获取信息
    ];

    public $actionUsingDefaultProcess = [
        'info' => ShareModel::SCE_GET_INFO,
        'detail' => ShareModel::SCE_GET_DETAIL,
        'wx-info' => ShareModel::SCE_GET_WX_INFO,
        '_model' => ShareModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionMemberInfo(){
        return $this->render('member_info');
    }

    public function actionInvitingFriends(){
        return $this->render('inviting_friends');
    }
}
