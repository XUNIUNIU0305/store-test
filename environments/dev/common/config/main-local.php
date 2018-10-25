<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=183.194.80.206;dbname=apex_platform',
            'username' => 'apex_platform',
            'password' => 'Apex_platform_13579246',
            'charset' => 'utf8mb4',
            'tablePrefix' => 'pf_',
        ],
        'amqp' => [
            'class' => 'common\components\Amqp',
            'host' => '183.194.80.206',
            'port' => '5672',
            'user' => 'apex',
            'passwd' => 'apex',
            'vhost' => '/',
        ],
        'session' => [
            'class' => 'yii\web\Session',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
