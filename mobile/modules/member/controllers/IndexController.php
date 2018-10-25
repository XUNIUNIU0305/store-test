<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/17
 * Time: 14:27
 */

namespace mobile\modules\member\controllers;




use mobile\modules\member\models\IndexModel;

class IndexController extends Controller
{


    protected $access=[
        'index'=>['@','get'],
        'get-user-info'=>['@','get'],
        'get-user-balance'=>['@','get'],
        'get-express' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess=[
        'get-user-info'=>IndexModel::SCE_GET_USER_INFO,
        'get-user-balance'=>IndexModel::SCE_GET_USER_BALANCE,
        'get-express' => [
            'scenario' => \custom\modules\account\models\IndexModel::SCE_GET_EXPRESS,
            'model' => \custom\modules\account\models\IndexModel::class,
        ],
        '_model'=>'mobile\modules\member\models\IndexModel',
    ];


    /**
     * Author:JiangYi
     * Date:2017/5/19
     * Desc:会员中心首页
     * @return string
     */
    public function actionIndex(){
        return $this->render("index");
    }

}
