<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-custom',
    'name' => '门店采购平台',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'account', 'guide', 'corporation', 'temp','quality', 'membrane', 'gpubs'],
    'controllerNamespace' => 'custom\controllers',
    'homeUrl' => '/',
    'components' => [
        'request' => [
            //'csrfParam' => '_csrf-custom',
        ],
        'user' => [
            'identityClass' => 'custom\models\parts\UserIdentity',
            'enableAutoLogin' => false,
            'loginUrl' => '/login',
            //'identityCookie' => ['name' => '_identity-custom', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-custom',
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
        'CustomUser' => [
            'class' => 'custom\components\CustomUser',
        ],
    ],
    'modules' => [
        'account' => [
            'class' => 'custom\modules\account\Module',
        ],
        'guide' => [
            'class' => 'custom\modules\guide\Module',
        ],
        'corporation' => [
            'class' => 'custom\modules\corporation\Module',
        ],
        'temp' => [
            'class' => 'custom\modules\temp\Module',
        ],
        'quality'=>[
            'class'=>'custom\modules\quality\Module',
        ],
        'membrane' => [
            'class' => 'custom\modules\membrane\Module'
        ],
        'gpubs' => [
            'class' => 'custom\modules\gpubs\Module',
        ],
    ],
    'params' => $params,
];
