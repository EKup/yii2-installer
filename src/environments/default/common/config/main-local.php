<?php

return [
    'components' => [
        'db' => [
            'dsn' => '#DB_TYPE#:host=#DB_HOST#;port=#DB_PORT#;dbname=#DB_NAME#',
            'username' => '#DB_LOGIN#',
            'password' => '#DB_PASSWORD#',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
