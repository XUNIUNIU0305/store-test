<?php
/**
 * User: JiangYi
 * Date: 2017/5/19
 * Time: 9:56
 * Desc:
 */

namespace mobile\controllers;
use Yii;
use mobile\models\GoodModel;

class GoodsController extends Controller
{

    protected  $access=[
        'index'=>[null,'get'],//商品首页
        'get-goods-info'=>[null,'get'],//获取商品详情
        'get-goods-list'=>[null,'get'],//获取商品列表
    ];

    protected  $actionUsingDefaultProcess=[
        'get-goods-info'=>GoodModel::SCE_GET_GOOD_INFO,
        '_model'=>'mobile\models\GoodModel',
    ];


    /**
     * Author:JiangYi
     * Date:2017/05/19
     * Desc:商品列表展示页视图
     * @return string
     */
    public function actionIndex(){
        return $this->render('index');
    }


    /**
     * Author:JiangYi
     * Date:2017/05/19
     * Desc:商户详情页面
     * @return string
     */
    public function actionDetail(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        return $this->render('detail');
    }


    //活动规则
    public function actionActivityRules(){
        return $this->render('activity_rules');
    }


}
