<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-wechat',
    'name' => '供应商微信平台',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'member', 'temp'],
    'controllerNamespace' => 'wechat\controllers',
    'defaultRoute' => 'index',
    'components' => [
        'user' => [
            'identityClass' => 'custom\models\parts\UserIdentity',
            'enableAutoLogin' => false,
//            'loginUrl' => 'login/wechat',
            'loginUrl'  => '/login'
//            'identityCookie' => ['name' => '_identity-wechat', 'httpOnly' => true],
        ],
        'errorHandler' => [
            'errorAction' => 'index/error',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-wechat',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                //common
                'captcha' => 'index/captcha',
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
        'member' => 'wechat\modules\member\Module',
        'temp' => 'wechat\modules\temp\Module',
    ],
    'params' => $params,
];
