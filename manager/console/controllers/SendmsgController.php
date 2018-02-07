<?php
namespace console\controllers;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 14:20
 */

use common\models\TbANewsLog;
use common\models\TbANews;
use common\models\TbUUser;
use common\models\TbUUserSetting;
use common\models\ZOrder;
use common\tools\DistStorage;
use Yii;
use yii\console\Controller;
use common\helpers\ArrayHelper;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SendmsgController extends Controller{
    /**
     * 推送消息
     */
    public function actionSend($id){
        ini_set('max_execution_time', '0');
        ini_set('memory_limit','512M');
        $form = TbANewsLog::find()->where(['id'=>$id,'status'=>1])->one();
        if($form){
            $model = TbANews::findOne($form->news_id);
            $title = base64_encode($model->title);
            $userIds = [];
            if($form->type == 1){ //全部
                $maxid = TbUUser::find()->max('id');
                $start = 0 ;
                $limit = 1000;
                while($start<=$maxid){
                    $members = TbUUser::find()
                        ->select('id,devicetoken,devicetype,mobile')
                        ->where("id > $start and id <= ".$start+$limit)
                        ->asArray()->all();
                    foreach($members as $key=>$val){
                        if (strlen($val["devicetoken"]) > 2){
                            //发送提醒
                            $this->send_notice($model,$val["mobile"],$val["devicetype"],$val["devicetoken"],$title,$val['id']);
                        }
                    }
                    $start+=$limit;
                }
            }elseif($form->type==2){ //按照身份
                $userIds = TbUUserSetting::find()->select('userid');
                if(!empty($form->province)){
                    $userIds = $userIds ->andWhere(['province'=>explode(',',$form->province)]);
                }
                if(!empty($form->exam_level)){
                    $userIds = $userIds ->andWhere(['exam_level'=>explode(',',$form->exam_level)]);
                }
                if(!empty($form->exam_type)){
                    $userIds = $userIds ->andWhere(['exam_type'=>$form->exam_type]);
                }
                $userIds = $userIds ->distinct()
                    ->asArray()
                    ->all();
            }elseif($form->type==3){ //按购买课程
                $userIds = ZOrder::find()->select('userid')->where("ClassID in (".$form->class.")")->distinct()->asArray()->all();
            }elseif($form->type==4){
                $userIds = ZOrder::find()->select('userid')->where("TelPhone in (".$form->phones.")")->distinct()->asArray()->all();
            }
            if(!empty($userIds)){
                $limit = 1000;
                $arr = [];
                foreach($userIds as $key=>$val){
                    $arr[] = $val['userid'];
                    unset($userIds[$key]);
                    if(count($arr)>=$limit){
                        $userInfos = TbUUser::find()->select('id,devicetoken,devicetype,mobile')->where(['id'=>$arr])->asArray()->all();
                        foreach($userInfos as $kk=>$vv){
                            if (strlen($vv["devicetoken"]) > 2){
                                //发送提醒
                                $this->send_notice($model,$vv["mobile"],$vv["devicetype"],$vv["devicetoken"],$title,$vv['id']);
                            }
                        }
                        $arr = [];
                    }
                }
                if(!empty($arr)){
                    $userInfos = TbUUser::find()->select('id,devicetoken,devicetype,mobile')->where(['id'=>$arr])->asArray()->all();
                    foreach($userInfos as $kk=>$vv){
                        if (strlen($vv["devicetoken"]) > 2){
                            //发送提醒
                            $this->send_notice($model,$vv["mobile"],$vv["devicetype"],$vv["devicetoken"],$title,$vv['id']);
                        }
                    }
                    $arr = [];
                }
            }
            $form -> status = 2;
            $form -> save(false);
        }

    }

    /**
     * 分类型推送
     *
     */
    private function send_notice($msg,$mobile,$devicetype,$devicetoken,$title,$userid){
        $redis = DistStorage::getQueueRedisConn();
        //发送提醒
        if ($devicetype == 1){
            //iOS
            if($msg->type == 1){
                $redis->rpush("market.list.apns.messagequeue",$mobile.":".$devicetoken.":".$msg->id.":".$title.":".$msg->type);
            }else if($msg->type == 3){
                $redis->rpush("market.list.apns.messagequeue",$mobile.":".$devicetoken.":".$msg->sourceid.":".$title.":".$msg->type);
            }else if($msg->type == 4){
                $redis->rpush("market.list.apns.messagequeue",$mobile.":".$devicetoken.":".$msg->sourceid.":".$title.":".$msg->type);
            }
        }else{
            //android
            if($msg->type == 1){
                $redis->rpush("market.list.umeng.messagequeue",$mobile.":".$devicetoken.":".$msg->id.":".$title.":".$msg->type);
            }else if($msg->type == 3){
                $redis->rpush("market.list.umeng.messagequeue",$mobile.":".$devicetoken.":".$msg->sourceid.":".$title.":".$msg->type);
            }else if($msg->type == 4){
                $redis->rpush("market.list.umeng.messagequeue",$mobile.":".$devicetoken.":".$msg->sourceid.":".$title.":".$msg->type);
            }
        }
        $notice_redis = DistStorage::getRedisConn(3,$userid);
        $user_msg_data = $notice_redis -> hget('USER_MESSAGE_'.$userid,"type_1");
        if($user_msg_data){
            $user_msg_data = json_decode($user_msg_data,true);
            $user_msg_data[$msg->id] = ['status'=>0];
            krsort($user_msg_data);
            if(count($user_msg_data) > 30){
                $num = 0 ;
                foreach($user_msg_data as $key => $item){
                    if($num >= 30){
                        unset($user_msg_data[$key]);
                    }
                    $num++;
                }
            }
        }else{
            $user_msg_data[$msg->id] = ['status'=>0];
        }
        $notice_redis -> hset('USER_MESSAGE_'.$userid,"type_1",json_encode($user_msg_data));
    }
}