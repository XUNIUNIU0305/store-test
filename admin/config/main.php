<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-admin',
    'name' => '网站管理平台',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => ['log', 'site', 'info','count','service','activity', 'nanjing', 'homepage', 'fund'],
    'modules' => [
        'site' => 'admin\modules\site\Module',
        'info' => 'admin\modules\info\Module',
        'count' => 'admin\modules\count\Module',
        'service'=>'admin\modules\service\Module',
        'activity'=>'admin\modules\activity\Module',
        'nanjing' => 'admin\modules\nanjing\Module',
        'homepage' => 'admin\modules\homepage\Module',
        'fund' => 'admin\modules\fund\Module',
    ],
    'components' => [
        'request' => [
            //'csrfParam' => '_csrf-admin',
        ],

        'user' => [
            'identityClass' => 'admin\models\parts\UserIdentity',
            'enableAutoLogin' => false,
            'loginUrl' => '/',
            //'identityCookie' => ['name' => '_identity-admin', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-admin',
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
        'AdminUser' => [
            'class' => 'admin\components\AdminUser',
        ],
    ],
    'params' => $params,
];
