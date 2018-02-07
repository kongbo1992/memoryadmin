<?php

/*
 * @description   文件上传方法
 * @author widuu  http://www.widuu.com
 * @mktime 08/01/2014
 */
 
global $QINIU_ACCESS_KEY;
global $QINIU_SECRET_KEY;

$QINIU_UP_HOST	= 'http://up.qiniu.com';
$QINIU_RS_HOST	= 'http://rs.qbox.me';
$QINIU_RSF_HOST	= 'http://rsf.qbox.me';

/*
//配置$QINIU_ACCESS_KEY和$QINIU_SECRET_KEY 为你自己的key
$QINIU_ACCESS_KEY	= '1_dgGJnVbsENFOttA4vCH-Oxi_wPqPp7tfgbf4Tn';
$QINIU_SECRET_KEY	= 'xCmzF2GRxA82vGpunh4mRoekImfq1VVnm4vyiC3e';

//配置bucket为你的bucket
$BUCKET = "wo2jiaoshiceshi";

//配置你的域名访问地址
$HOST  = "http://7xo0il.com1.z0.glb.clouddn.com";
*/
//配置$QINIU_ACCESS_KEY和$QINIU_SECRET_KEY 为你自己的key
$QINIU_ACCESS_KEY = 'Xw0U1GcFYDsww8rlKGIYiHPtKxTaZ0PrJGQ6LFc7';
$QINIU_SECRET_KEY = 'figOWd7Jzf7lF9Hm4pCC3K5ezpiXu1nI0ZI-ubQI';

//配置bucket为你的bucket
$BUCKET = "jiaoshi";

//配置你的域名访问地址
$HOST = "http://7xodvc.com2.z0.glb.qiniucdn.com";

//上传超时时间
$TIMEOUT = "3600";

//保存规则
$SAVETYPE = "date";

//开启水印
$USEWATER = false;
$WATERIMAGEURL = "http://gitwiduu.u.qiniudn.com/ueditor-bg.png"; //七牛上的图片地址
//水印透明度
$DISSOLVE = 50;
//水印位置
$GRAVITY = "SouthEast";
//边距横向位置
$DX  = 10;
//边距纵向位置
$DY  = 10;

function urlsafe_base64_encode($data){
	$find = array('+', '/');
	$replace = array('-', '_');
	return str_replace($find, $replace, base64_encode($data));
}


