<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/28 0028
 * Time: 11:48
 */

namespace mobile\modules\membrane\controllers;

use mobile\modules\membrane\models\HomeModel;
use common\models\parts\custom\CustomUser;
use business\models\parts\Area;

class HomeController extends Controller
{
    protected $access = [
        'index' => ['@', 'get'],
        'address' => ['@', 'get'],
        'payment' => ['@', 'get'],
        'order' => ['@', 'post'],
        'status' => ['@', 'get'],
        'balance' => ['@', 'get'],
        'validate' => ['@', 'get'],
        'product' => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'address' => HomeModel::SCE_ADDRESS,
        'payment' => HomeModel::SCE_PAYMENT,
        'order' => HomeModel::SCE_MOBILE_ORDER,
        'status' => HomeModel::SCE_STATUS,
        'balance' => HomeModel::SCE_BALANCE,
        'product' => HomeModel::SCE_PRODUCT,
        '_model' => HomeModel::class
    ];

    public function actionIndex()
    {
        $this->layout = 'base';
        return $this->render('index');
    }

    public function actionOrder()
    {
        if($this->validateUser() === false)
            return $this->failure(10050);
        $model = new HomeModel([
            'scenario' => HomeModel::SCE_MOBILE_ORDER,
            'attributes' => \Yii::$app->request->post()
        ]);
        if($res = $model->process()){
            return $this->success($res);
        }
        return $this->failure($model->getErrorCode());
    }

    /**
     * @return \common\controllers\json
     * 验证权限
     */
    public function actionValidate()
    {
        if($this->validateUser() === false)
            return $this->failure(10050);
        return $this->success([]);
    }

    private function validateUser()
    {
        $user = \Yii::$app->CustomUser->CurrentUser;
        if($user->getLevel() < CustomUser::LEVEL_PARTNER || $user->getArea()->id == Area::DEFAULT_FIFTH_ID)
            return false;
    }
}
