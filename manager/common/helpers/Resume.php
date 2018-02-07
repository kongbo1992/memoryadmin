<?php
namespace common\helpers;

use common\tools\DistStorage;
use Yii;
use yii\db\Exception;
use backend\modules\zhaopin\models\JobsJob;
use backend\modules\zhaopin\models\JobsUserJobPost;
class Resume
{
//    简历详情处理
    public static function Details($id)
    {
        $sql = "select * from jobs_user_info WHERE userid = ".$id;
        $info = Yii::$app->db->createCommand($sql)->queryOne();
        if($info){
            if($info['gender']==1){
                $info['gender']="男";
            }else{
                $info['gender']="女";
            }
            if($info['cert']){
                $info['cert'] = JobsJob::get_level($info['cert']);
            }else{
                $info['cert'] = "未设置";
            }
            if($info['cert_subject']){
                $info['cert_subject'] = JobsJob::get_subject($info['cert_subject']);
            }else{
                $info['cert_subject'] = "未设置";
            }
            if($info['expect_salary']){
                $info['expect_salary'] = JobsJob::get_money($info['expect_salary']);
            }else{
                $info['expect_salary'] = "未设置";
            }
        }
        $eduhistory = (new \yii\db\Query())
            ->from('jobs_user_eduhistory')
            ->andWhere(['userid'=>$id])
            ->all();
        foreach($eduhistory as $k=>$v){
            if($v['edurecord']==1){
                $eduhistory[$k]['edurecord'] = "中专及以下";
            }elseif($v['edurecord']==2){
                $eduhistory[$k]['edurecord'] = "大专";
            }elseif($v['edurecord']==3){
                $eduhistory[$k]['edurecord'] = "本科";
            }elseif($v['edurecord']==4){
                $eduhistory[$k]['edurecord'] = "硕士";
            }elseif($v['edurecord']==5){
                $eduhistory[$k]['edurecord'] = "博士";
            }else{
                $eduhistory[$k]['edurecord'] = "其他";
            }
        }

        $workhistory = (new \yii\db\Query())
            ->from('jobs_user_workhistory')
            ->andWhere(['userid'=>$id])
            ->all();

        $sql ="SELECT t.orgid,t.jobid,t.opertype,t.id,t.state,z.title,x.org_name,t.posttime,x.org_linkman,x.org_phone from jobs_user_job_post as t INNER JOIN jobs_job AS z ON t.jobid=z.id INNER JOIN jobs_org as x ON t.orgid=x.id WHERE t.userid=$id";
        $post = Yii::$app->db->createCommand($sql)->queryAll();
        $post_1 = [];
        $post_2 = [];
        foreach($post as $k=>$v){
            $v['state'] = JobsUserJobPost::get_post_state($v['state']);
            if($v['opertype']==3){
                $post_1[] = $v;
            }else{
                $post_2[] = $v;
            }
        }
        return [
            'post_1' => $post_1,
            'post_2' => $post_2,
            'eduhistory' => $eduhistory,
            'workhistory' => $workhistory,
            'info' => $info
        ];
    }

    /**发给企业简历**/
    public static function sendMail_resume($to,$orgname,$jobtitle,$resumestr,$postname){
        $body = '<p>'.$orgname.'，您好<br/></p>'.
            '<p><br/></p>
                <p>&nbsp; &nbsp; 您在52招聘网发布的 "'.$jobtitle.'" 职位收到 "'.$postname.'" 的简历，推荐给您,请及时联系,谢谢！<br/></p>
                <p><br/></p>'.
            $resumestr.
            '<p>&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 14px;">本邮件由52招聘网发送，<span style="color: rgb(255, 0, 0);">52招聘——专业的教师人才招聘网站</span>。</span><br/></p>
                <p><br/></p>
                <hr/>
                <p><span style="font-size: 14px;">如有任何疑问，可以与我们联系，我们会尽快为您解决。</span></p>
                <p><span style="font-size: 14px;">电话：4006-9678-006 邮箱：zhaopin@52jiaoshi.com</span></p>';
//        return $this->sendMail($to, $orgname, , $body);
        return \Yii::$app->mailer->compose()
            ->setFrom('zhaopin@52jiaoshi.com')
            ->setTo($to)
            ->setSubject($jobtitle.":".$postname."的简历(来自52招聘)")
            ->setHtmlBody($body)
            ->send();

    }

    public static function scenario(){
//        $jobs = (new yii)
    }

  } 
  
      