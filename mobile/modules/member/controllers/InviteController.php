<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/7/26
 * Time: 下午2:47
 */

namespace mobile\modules\member\controllers;


use mobile\modules\member\models\InviteModel;
use Yii;

class InviteController extends Controller
{
    protected $access = [
        'code'=>['@','get'],//获取二维码
        'index'=>['@','get'],
        'invite-log'=>['@','get'],//邀请纪录
        'invite-num'=>['@','get'],//数量
        'code-info'=>['@','get'],//数量
    ];
    protected $actionUsingDefaultProcess = [
        'invite-log'=>InviteModel::SCE_INVITE_LOG,
        'invite-num'=>InviteModel::SCE_NUM,
        'code-info'=>InviteModel::SCE_CODE_INFO,
        '_model'=>'mobile\modules\member\models\InviteModel',
    ];


    //二维码
    public function actionIndex(){
        return $this->render('index');
    }

    //邀请纪录列表
    public function actionList(){
        return $this->render('list');
    }


    public function actionCode(){
        $model = new InviteModel([
            'scenario'=>InviteModel::SCE_CODE_IMG,
            'attributes'=>Yii::$app->request->get(),
        ]);
        return $model->getCodeImg();
    }

}