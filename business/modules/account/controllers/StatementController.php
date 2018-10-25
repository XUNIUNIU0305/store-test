<?php
/**
 * Created by PhpStorm.
 * User: tangzhaofeng
 * Date: 18-7-23
 * Time: 下午4:02
 */

namespace business\modules\account\controllers;

use Yii;
use common\controllers\Controller;
use business\modules\account\models\StatementModel;

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
        '_model' => '\business\modules\account\models\StatementModel',
    ];


    public function actionIndex(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        return $this->render('index');
    }
}