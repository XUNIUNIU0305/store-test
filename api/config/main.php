<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'name' => '接口',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',

    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
        ],
        //'user' => [
            //'identityClass' => 'supply\models\parts\UserIdentity',
            //'enableAutoLogin' => false,
            //'loginUrl' => '/',
            //'identityCookie' => ['name' => '_identity-supply', 'httpOnly' => true],
        //],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-api',
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

                '' => 'index',
                '<controller:[a-z-]+>' => '<controller>',
                '<controller:[a-z-]+>/<action:[a-z-]+>' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
