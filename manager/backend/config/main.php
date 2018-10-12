<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    "modules" => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        "admin" => [
            "class" => 'mdm\admin\Module',
        ],
        'manager' => [
            'class' => 'backend\modules\manager\Module',
        ],
        'models' => [
            'class' => 'backend\modules\models\Module',
        ],
        'customer' => [
            'class' => 'backend\modules\customer\Module',
        ],
    ],
    "aliases" => [
        "@mdm/admin" => "@vendor/mdmsoft/yii2-admin",
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            //这里是允许访问的action
            //controller/action
            // * 表示允许所有，后期会介绍这个
            '*',
            'qiniu/*',
            'site/*',
            'gridview/*',
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'backend\models\Manager',
            'enableAutoLogin' => false,
            'enableSession' => true,
            'absoluteAuthTimeout' => strtotime(date("Y-m-d"))+86400-time(),
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'logFile' => "@runtime/logs/".date("Y-m-d").".log",
                    'maxFileSize' => 10240
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        "authManager" => [
            "class" => 'yii\rbac\DbManager',
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ],
            "defaultRoles" => ["guest"],
        ],
        'qiniu' => [
            'class' => 'chocoboxxf\Qiniu\Qiniu',
            'accessKey' => 'ALs5wabsph05KbKXV0V0LRt5_W8gTS5iUZH8QH8r',
            'secretKey' => 'ZwvmuTVOVOtl1sw05Sjc9-7Zst1G5f-Jvruhcmfn',
            'domain' => 'oz37p8kpu.bkt.clouddn.com',
            'bucket' => 'memory',
            'secure' => false, // 是否使用HTTPS，默认为false
        ],
//        'qiniuzp' => [
//            'class' => 'chocoboxxf\Qiniu\Qiniu',
//            'accessKey' => 'Xw0U1GcFYDsww8rlKGIYiHPtKxTaZ0PrJGQ6LFc7',
//            'secretKey' => 'figOWd7Jzf7lF9Hm4pCC3K5ezpiXu1nI0ZI-ubQI',
//            'domain' => 'zhaopinimg.52jiaoshi.com',
//            'bucket' => 'zhaopin',
//            'secure' => false, // 是否使用HTTPS，默认为false
//        ],
//        'qiniukl' => [
//            'class' => 'chocoboxxf\Qiniu\Qiniu',
//            'accessKey' => 'Xw0U1GcFYDsww8rlKGIYiHPtKxTaZ0PrJGQ6LFc7',
//            'secretKey' => 'figOWd7Jzf7lF9Hm4pCC3K5ezpiXu1nI0ZI-ubQI',
//            'domain' => 'audio.52jiaoshi.com',
//            'bucket' => 'audio',
//            'secure' => false, // 是否使用HTTPS，默认为false
////            'HOST' => "http://audio.52jiaoshi.com",
//        ],

    ],
    'language'=>'zh-CN',
    'params' => $params,

];
