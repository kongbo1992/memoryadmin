<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'domain' => 'http://apptest.52jiaoshi.com',
    'admin_root_path' => '/home/wwwroot/admin_test/',
    'main_redis_conf' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'auth' => 'Born'
    ],
    'queue_redis_conf' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'auth' => 'Born'
    ],
    "REDIS_HOST_COUNT_1" => 2 ,// 主redis
    "REDIS_HOST1:1" => "127.0.0.1",
    "REDIS_HOST_PORT1:1" => 6379,
    "REDIS_HOST1:2" => "127.0.0.1",
    "REDIS_HOST_PORT1:2" => 6380,

    "REDIS_HOST_COUNT_2" => 2 ,// 模考数据
    "REDIS_HOST2:1" => "127.0.0.1",
    "REDIS_HOST_PORT2:1" => 6381,
    "REDIS_HOST2:2" => "127.0.0.1",
    "REDIS_HOST_PORT2:2" => 6382,

    "REDIS_HOST_COUNT_3" => 2 ,// 课程相关数据
    "REDIS_HOST3:1" => "10.172.233.222",
    "REDIS_HOST_PORT3:1" => 6383,
    "REDIS_HOST3:2" => "10.172.233.222",
    "REDIS_HOST_PORT3:2" => 6384,

    "REDIS_HOST_COUNT_4" => 2 ,// 历史
    "REDIS_HOST4:1" => "10.172.233.222",
    "REDIS_HOST_PORT4:1" => 6385,
    "REDIS_HOST4:2" => "10.172.233.222",
    "REDIS_HOST_PORT4:2" => 6386,

    "REDIS_HOST_COUNT_101" => 1 ,// 招聘redis
    "REDIS_HOST101:1" => "127.0.0.1",
    "REDIS_HOST_PORT101:1" => 6379,

    "REDIS_HOST_COUNT_201" => 1 ,// 招聘redis
    "REDIS_HOST201:1" => "127.0.0.1",
    "REDIS_HOST_PORT201:1" => 6379,

    'DB_CONFIG_ELASTICSEARCH' => array(
        'DB_HOST' => '127.0.0.1',
        'DB_PORT' => 9200,
        'DB_INDEX' => 'zhaopin_apptest',
        'DB_TABLE' => 'jobs_test',
    ),
    'unit'=>[
        1=>"个",
        2=>"桶",
        3=>"只",
        4=>"双",
        5=>"斤",
        6=>"米",
        7=>"箱",
        8=>"盒",
    ],
    'exam_province'=>array(
        1 => '安徽',2 => '北京',3 => '重庆',
        4 => '福建',5 => '甘肃',6 => '广东',
        7 => '广西',8 => '贵州',9 => '河南',
        10 => '海南',11 => '河北',12 => '黑龙江',
        13 => '湖北',14 => '湖南',15 => '江苏',
        16 => '江西',17 => '吉林',18 => '辽宁',
        19 => '内蒙古',20 => '宁夏',21 => '青海',
        22 => '陕西',23 => '山西',24 => '山东',
        25 => '上海',26 => '四川',27 => '天津',
        28 => '新疆',29 => '西藏',30 => '云南',31 => '浙江'
    ),

];
