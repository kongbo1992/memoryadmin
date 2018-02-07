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
    'question_type'=>[
        1=>"单项选择题",
        2=>"多项选择题",
        3=>"判断题",
        4=>"辨析题",
        5=>"简答题",
        6=>"作文题",
        7=>"填空题",
        8=>"不定项材料分析题",
        9=>"活动设计题",
        10=>"名词解释",
        11=>"材料分析题",
        12=>"论述题",
        13=>"不定项选择题",
        14=>"公文改错题",
        15=>"教学设计题"
    ],
    'exam_level'=>array(
        1 => '学前',
        2 => '小学',
        3 => '初中',
        4 => '高中'
    ),
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
    'exam_type'=>array(
        1 => '教师资格证',
        2 => '教师招聘',
    ),
    'exam_subject'=>array(
        1   =>  '语文',
        2   =>  '数学',
        3   =>  '英语',
        4   =>  '物理',
        5   =>  '化学',
        6   =>  '生物',
        7   =>  '历史',
        8   =>  '地理',
        9   =>  '音乐',
        10  =>  '体育与健康',
        11  =>  '美术',
        12  =>  '信息技术',
        13  =>  '思想品德',
        14  =>  '历史与社会',
        15  =>  '科学',
        16  =>  '通用技术',
    ),
    'class_type' => array(
        1 => '笔试',
        2 => '面试-说课',
        3 => '面试-试讲',
        4 => '面试-结构化',
    ),
	'teaching_version' => array(
        1 => '人教版',
        2 => '苏教版',
        3 => '鲁教版',
        4 => '湘教版',
        5 => '浙教版',
        6 => '沪教版',
        7 => '粤教版',
        8 => '北师大版',
        9 => '外研社',
        10 => '人美版',
        11 => '人音版',
        12 => '青岛版',
        13 => '岳麓版',
        14 => '其他',
    ),
    '1v1_classname' => array(
        1 => '面试理论一',
        2 => '面试理论二',
        3 => '教材串讲',
        4 => '练习一',
        5 => '练习二',
        6 => '练习三',
        7 => '练习四',
        8 => '全真模拟',
    ),
];
