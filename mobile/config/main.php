<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-mobile',
    'name' => '微信平台',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'member', 'temp', 'membrane', 'customization', 'activity', 'gpubs'],
    'controllerNamespace' => 'mobile\controllers',
    'homeUrl' => '/',
    'components' => [
        'request' => [
            //'csrfParam' => '_csrf-custom',
        ],
        'user' => [
            'class' => 'mobile\components\User',
            'identityClass' => 'custom\models\parts\UserIdentity',
            'enableAutoLogin' => false,
            'loginUrl' => '/member/login/index',//如果未登录，返回首页
            //'identityCookie' => ['name' => '_identity-custom', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-mobile',
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
                '' => 'index',
                'api-hostname' => 'index/api-hostname',
                '<controller:[a-z-]+>' => '<controller>',
                '<controller:[a-z-]+>/<action:[a-z-]+>' => '<controller>/<action>',
            ],
        ],
        'CustomUser' => [
            'class' => 'custom\components\CustomUser',
        ],
    ],
    'modules' => [
        'member' => 'mobile\modules\member\Module',
        'temp' => 'mobile\modules\temp\Module',
        'membrane' => 'mobile\modules\membrane\Module',
        'customization' => 'mobile\modules\customization\Module',
        'activity' => 'mobile\modules\activity\Module',
        'gpubs' => 'mobile\modules\gpubs\Module',
    ],
    'params' => $params,
];
