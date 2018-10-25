<?php
namespace admin\controllers;

use Yii;
use mobile\models\WxpayModel;
use yii\web\ForbiddenHttpException;
use common\controllers\Controller;

class WxpayController extends Controller{

    protected $access = [
        'index' => [null, 'get'],
    ];

    public $layout = false;

    public function actionIndex(){
        if($params = WxpayModel::getPayParams()){
            return $this->render('index', [
                'params' => $params,
                'callback' => [
                    'success' => '/partner/success?a=' . Yii::$app->session->get('__partner_apply_id', ''),
                    'fail' => '/partner/fail',
                ],
            ]);
        }else{
            throw new ForbiddenHttpException;
        }
    }
}
