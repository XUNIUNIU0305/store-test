<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/7/5
 * Time: 下午2:15
 */

namespace supply\controllers;


use common\controllers\Controller;
use supply\models\PrintModel;

class PrintController extends Controller
{
    protected $access = [
        'print' => ['@', 'get'],
        'print-order' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'print-order'=>PrintModel::SCE_PRINT_ORDER,
        '_model' => 'supply\models\PrintModel',
    ];

    /**
     *====================================================
     * 打印订单页面
     * @return string
     * @author shuang.li
     * @date 2017年6月7日
     *====================================================
     */
    public function actionIndex(){
        $this->layout = 'print';
        return $this->render('index');
    }
}