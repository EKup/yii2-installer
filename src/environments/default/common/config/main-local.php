<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => '#DB_TYPE#:host=#DB_HOST#;port=#DB_PORT#;dbname=#DB_NAME#',
            'username' => '#DB_LOGIN#',
            'password' => '#DB_PASSWORD#',
            'charset' => 'utf8',
            'enableSchemaCache' => !YII_DEBUG,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
