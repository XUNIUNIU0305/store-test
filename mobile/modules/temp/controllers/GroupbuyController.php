<?php
namespace mobile\modules\temp\controllers;

use Yii;
use common\controllers\Controller;
use mobile\modules\temp\models\GroupbuyModel;

class GroupbuyController extends Controller
{
    protected $access = [
        'get-all-groupbuy'              => [null, 'get'],
        'is-groupbuy'                   => [null, 'get'],
        'get-all-groupbuy-specific'     => [null, 'get'],
        'get-unix-timestamp'            => [null, 'get'],
        'is-login'                      => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'get-all-groupbuy'          => GroupbuyModel::SCE_GET_ALL_GROUPBUY,
        'get-all-groupbuy-specific' => GroupbuyModel::SCE_GET_ALL_GROUPBUY_SPECIFIC,
        'is-groupbuy'               => GroupbuyModel::SCE_IS_GROUPBUY,
        '_model'                    => GroupbuyModel::class,
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetUnixTimestamp()
    {
        return $this->success(['unix_timestamp' => time()]);
    }
    
    public function actionIsLogin()
    {
        return $this->success(['is-login' => !Yii::$app->user->getIsGuest()]);
    }
    
    public function actionGetAllGroupbuySpecific()
    {
        if(Yii::$app->user->getIsGuest()) {
            return $this->redirect('/member/auth');
        }
        
        $groupbuyModel = new GroupbuyModel([
            'scenario' => GroupbuyModel::SCE_GET_ALL_GROUPBUY_SPECIFIC,
            'attributes' => ['groupbuy_id' => Yii::$app->request->get('groupbuy_id')],
        ]);
        
        if(($info = $groupbuyModel->process()) !== false){
            return $this->success($info);
        }else{
            return $this->failure($groupbuyModel->errorCode);
        }
    }
}
