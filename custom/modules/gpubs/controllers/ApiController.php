<?php
namespace custom\modules\gpubs\controllers;

use Yii;
use common\controllers\Controller;
use custom\modules\gpubs\models\ApiModel;

class ApiController extends Controller{

    public $access = [
        'product' => [null, 'get'],
        'get-gpubs-time' => [null,'get'],
        'group' => ['@', 'get'],
        'order-list' => ['@', 'get'],
        'order-detail' => ['@', 'get'],
        'join-failed-list' => ['@', 'get'],
        'activity-group-list' => ['@', 'get'],
        'activity-group-detail' => ['@', 'get'],
        'get-trade' => ['@','get'],
    ];

    public $actionUsingDefaultProcess = [
        'product' => ApiModel::SCE_GET_PRODUCT,
        'get-gpubs-time' => ApiModel::SCE_GET_GPUBS_TIME,
        'group' => ApiModel::SCE_GET_GROUP,
        'order-list' => ApiModel::SCE_GET_ORDER_LIST,
        'order-detail' => ApiModel::SCE_GET_ORDER_DETAIL,
        'join-failed-list' => ApiModel::SCE_GET_JOIN_FAILED_LIST,
        'activity-group-list' => ApiModel::SCE_ACTIVITY_GROUP_LIST,
        'activity-group-detail' => ApiModel::SCE_ACTIVITY_GROUP_DETAIL,
        'get-trade' => ApiModel::SCE_GET_TRADE,
        '_model' => ApiModel::class,
    ];
}
