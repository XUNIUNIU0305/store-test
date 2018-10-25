<?php
/**
 * Created by PhpStorm.
 * User: tangzhaofeng
 * Date: 18-9-10
 * Time: 下午6:30
 */
namespace supply\controllers;

use common\models\parts\ExpressCorporation;
use common\models\parts\SupplyUserExpress;
use custom\modules\account\models\IndexModel;
use Yii;
use common\controllers\Controller;
use supply\models\GpubsModel;

class GpubsController extends Controller
{
    public $layout = 'main';

    protected $access = [
        'index' => ['@', 'get'],
        'list' => ['@', 'get'],
        'quantity' => ['@','get'],
        'deliver' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'list'=>GpubsModel::SCE_GET_LIST,
        'quantity'=> GpubsModel::SCE_GET_QUANTITY,
        '_model'=>'supply\models\GpubsModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionDeliver(){
        $gpubsModel = new GpubsModel([
            'scenario' => GpubsModel::SCE_SET_DELIVERED,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($gpubsModel->process()){
            return $this->success([]);
        }else{
            return $this->failure($gpubsModel->errorCode);
        }
    }
}