<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=rdsry98ufk5y0czny2ux.mysql.rds.aliyuncs.com;dbname=woijiaoshi',
            'username' => 'woijiaoshi',
            'password' => 'Bornedu345',
            'charset' => 'utf8',
        ],
        'db_lower' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=rdsry98ufk5y0czny2ux.mysql.rds.aliyuncs.com;dbname=woijiaoshi',
            'username' => 'woijiaoshi',
            'password' => 'Bornedu345',
            'charset' => 'utf8',
            'attributes'=>[
                \PDO::ATTR_CASE=>\PDO::CASE_LOWER
            ]
        ],
//        'redis' => [
//            'class' => 'yii\redis\Connection',
//            'hostname' => '123.57.18.82',
//            'port' => 6379,
//            'database' => 0,
//            'password'  => 'Born'
//        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.exmail.qq.com',
                'username' => 'zhaopin@52jiaoshi.com',
                'password' => 'Born_j567',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['zhaopin@52jiaoshi.com'=>'52招聘']
            ],
        ],
    ],
];
