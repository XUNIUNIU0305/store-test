<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-business',
    'name' => '线下业务',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'leader', 'site', 'account', 'quality', 'temp', 'membrane', 'data', 'bank'],
    'modules' => [
        'leader' => 'business\modules\leader\Module',
        'site' => 'business\modules\site\Module',
        'account' => 'business\modules\account\Module',
        'quality' => 'business\modules\quality\Module',
        'temp' => 'business\modules\temp\Module',
        'membrane' => 'business\modules\membrane\Module',
        'data' => 'business\modules\data\Module',
        'bank' => 'business\modules\bank\Module',
    ],
    'controllerNamespace' => 'business\controllers',
    'homeUrl' => '/',
    'components' => [
        'request' => [
            //'csrfParam' => '_csrf-business',
        ],
        'user' => [
            'accessChecker' => 'business\models\parts\AccessChecker',
            'identityClass' => 'business\models\parts\UserIdentity',
            'enableAutoLogin' => false,
            'loginUrl' => '/',
        ],
        'BusinessUser' => 'business\components\BusinessUser',
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-business',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'index/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                //common
                'captcha' => 'index/captcha',
                'error' => 'index/error',
                'api-hostname' => 'index/api-hostname',
                '' => 'index',
                '<controller:[a-z-]+>' => '<controller>',
                '<controller:[a-z-]+>/<action:[a-z-]+>' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
