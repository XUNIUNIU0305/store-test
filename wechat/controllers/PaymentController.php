<?php
/**
 * User: JiangYi
 * Date: 2017/5/19
 * Time: 9:56
 * Desc:
 */

namespace mobile\controllers;


class PaymentController extends AuthorController
{

    protected  $access=[
        'index'=>['@','get'],//待定
        'result'=>['@','get'],//支付结果
    ];

    protected  $actionUsingDefaultProcess=[

    ];


    /**
     * Author:JiangYi
     * Date:2017/05/19
     * Desc:支付完成回调页面
     * @return string
     */
    public function actionResult(){
        return $this->render('result');
    }

}