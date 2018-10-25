<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 14/05/18
 * Time: 15:50
 */

namespace custom\modules\quality\controllers;

use common\controllers\Controller;
use custom\modules\quality\models\QualitySearchModel;
use Yii;

class QualitySearchController extends Controller
{
    public $layout = 'quality-search';

    protected $access = [
        'send-mobile-captcha' => [null, 'get'],
        'auth-by-owner' => [null, 'post'],
        'list-by-owner' => [null, 'get'],
        'detail-by-owner' => [null, 'get'],
        'auth-by-custom' => [null, 'post'],
        'search-by-ordercode' => [null, 'get'],
        'list-by-itemcode' => [null, 'get'],
        'detail-by-itemcode' => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'send-mobile-captcha' => QualitySearchModel::SCE_SEND_MOBILE_CAPTCHA,
        'auth-by-owner' => QualitySearchModel::SCE_AUTH_BY_OWNER,
        'list-by-owner' => QualitySearchModel::SCE_LIST_BY_OWNER,
        'detail-by-owner' => [
            'scenario' => QualitySearchModel::SCE_DETAIL_BY_OWNER,
            'convert' => false,
        ],
        'auth-by-custom' => QualitySearchModel::SCE_AUTH_BY_CUSTOM,
        'search-by-ordercode' => QualitySearchModel::SCE_SEARCH_BY_ORDERCODE,
        'list-by-itemcode' => QualitySearchModel::SCE_LIST_BY_ITEMCODE,
        'detail-by-itemcode' => QualitySearchModel::SCE_DETAIL_BY_ITEMCODE,
        '_model'=>'custom\modules\quality\models\QualitySearchModel'
    ];

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/quality/quality-search/custom-search');
        }
        $this->layout = 'empty';
        return $this->render('auth');
    }

    public function actionOwnerList()
    {
        return $this->render('owner_list');
    }

    public function actionOwnerDetail()
    {
        return $this->render('owner_detail');
    }

    public function actionCustomSearch()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/quality/quality-search/index');
        }
        return $this->render('custom_search');
    }

    public function actionCustomDetail()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/quality/quality-search/index');
        }
        return $this->render('custom_detail');
    }
}