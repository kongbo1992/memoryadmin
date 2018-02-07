<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 14:20
 */

namespace console\controllers;
use Yii;
use yii\console\Controller;
use common\helpers\SendRdyxMsg;
use common\tools\Elasticsearch;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class NoticeController extends Controller
{
    public function actionNotice(){
        $message = new SendRdyxMsg();
        $updatejob = new Elasticsearch();
        $time = date("Y-m-d ",strtotime('-1 day'));
        $sql = "SELECT j.id,j.orgid,j.end_time,o.org_phone from jobs_job as j INNER JOIN jobs_org as o ON j.orgid = o.id WHERE j.end_time <='".date("Y-m-d ")."' and (j.job_state = 2 or j.job_state = 3) and (j.end_time <> '' or j.end_time is NOT NULL) and (o.user_mobile = '' or o.user_mobile IS NULL)";
        $jobs = Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($jobs)){
            $org = [];
            $ids = [];
            $org_ids = [];
            foreach($jobs as $key => $val){
                $org[$val['orgid']] = $val['org_phone'];
                if(!empty($val['end_time']) && $val['end_time'] <= $time){
                    $ids[] = $val['id'];
                }else{
                    $org_ids[] = $val['orgid'];
                }
            }
//            修改搜索引型
            $ids = array_unique($ids);
//            发送通知短信
            $org_ids = array_unique($org_ids);
            $data = $updatejob->delJobByIds($ids);
            foreach($org_ids as $key => $val){
                $message->message_class($org[$val],"校长您好，贵校的招聘岗位将在24小时后截止，如需继续招聘请联系15606392787（9:00至18:00）！",3);
            }
            $sql = "update jobs_job set job_state = 4 WHERE id in (".implode(',',$ids).")";
            Yii::$app->db->createCommand($sql)->execute();
        }
        return true;

    }


}
