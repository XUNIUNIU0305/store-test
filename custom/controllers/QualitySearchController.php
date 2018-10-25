<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/9/8
 * Time: 上午11:26
 */

namespace custom\controllers;
use common\controllers\Controller;
use custom\models\QualitySearchModel;

class QualitySearchController extends Controller{
    public $layout = 'global';
    protected $access = [
        'index' => [null, 'get'],
        'search-detail-one'=>[null,'get'],
        'search-detail-two'=>[null,'get'],
        'search-detail-three'=>[null,'get'],
        'search-detail-four'=>[null,'get'],
     ];

    protected $actionUsingDefaultProcess = [
        'search-detail-one'=>[
            'scenario'=>QualitySearchModel::SCE_SEARCH_DETAIL_ONE,
            'convert'=>false
        ],
        'search-detail-two'=>QualitySearchModel::SCE_SEARCH_DETAIL_TWO,
        'search-detail-three'=>[
            'scenario'=> QualitySearchModel::SCE_SEARCH_DETAIL_THREE,
            'convert'=>false
        ],
        'search-detail-four'=> [
            'scenario'=> QualitySearchModel::SCE_SEARCH_DETAIL_FOUR,
            'convert'=>false
        ],
        '_model'=>'custom\models\QualitySearchModel'
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}