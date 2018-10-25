<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/27
 * Time: 上午10:42
 */

namespace custom\modules\account\controllers;

use Yii;
use common\controllers\Controller;
use custom\modules\account\models\StatementModel;

class StatementController extends Controller
{

    protected $access = [
        'list' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => [
            'scenario'=>StatementModel::SCE_GET_LIST,
            'convert'=>false,
        ],
        '_model' => '\custom\modules\account\models\StatementModel',
    ];


    public function actionIndex(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        return $this->render('index');
    }
}