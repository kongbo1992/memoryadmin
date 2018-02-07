<?php
namespace common\helpers;

use common\tools\DistStorage;
use Yii;
use yii\db\Exception;
use backend\modules\zhaopin\models\JobsJob;
class Email
{
//    教师招聘模考展示
    public static function PushEmail($id)
    {
        $info = \backend\modules\zhaopin\models\JobsUserInfo::find()
            ->where(['userid' => $id])
            ->one();
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
        $workhistory = \backend\modules\zhaopin\models\JobsUserWorkhistory::find()
            ->where(['userid' => $id])
            ->asArray()
            ->all();
        $data_1 = '';
        foreach($workhistory as $key=>$val){
            $data_1.= '
    <ul class="resume_msg_gzjl " style="list-style-type: square;">
        <div style="overflow: hidden;">
            <p style="width: 350px; float: left; line-height: normal;margin: 5px 0;">
                <span style="box-sizing: border-box;  padding: 0px; color: rgb(160, 160, 160); display: inline-block;">公司名称：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$val["orgname"].'</span>
            </p>
            <p style=" line-height: normal;margin: 5px 0;">
                <span style="box-sizing: border-box; margin: 0 0; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">职位名称：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$val["title"].'</span>
            </p>
        </div>
        <div style="overflow: hidden;">
            <p style="width: 350px; float: left; line-height: normal;margin: 5px 0;">
                <span style="box-sizing: border-box; margin: 0 0; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">入职时间：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$val["startdate"].'</span>
            </p>
            <p style=" line-height: normal;margin: 5px 0;">
                <span style="box-sizing: border-box; margin: 0 0; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">离职时间：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$val["enddate"].'</span>
            </p>
        </div>
        <p style="line-height: normal;margin: 5px 0;margin-bottom:10px;">
            <span style="box-sizing: border-box; margin: 0 0; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">工作描述：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$val["desc"].'</span>
        </p>
    </ul>';
        }
        $eduhistory = (new \yii\db\Query())
            ->from('jobs_user_eduhistory')
            ->where(['userid' => $id])
            ->all();
        $data_2 = '';
        foreach($eduhistory as $key=>$val){
            if($val['edurecord']==1){
                $val['edurecord'] = "中专及以下";
            }elseif($val['edurecord']==2){
                $val['edurecord'] = "大专";
            }elseif($val['edurecord']==3){
                $val['edurecord'] = "本科";
            }elseif($val['edurecord']==4){
                $val['edurecord'] = "硕士";
            }elseif($val['edurecord']==5){
                $val['edurecord'] = "博士";
            }else{
                $val['edurecord'] = "其他";
            }
            $data_2.= '
    <ul class="resume_msg_jyjl list-paddingleft-2" style="list-style-type: square;">
            <p style="line-height: normal;margin: 5px 0;">
                <span style="box-sizing: border-box; margin: 0 0; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">学校名称：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$val["orgname"].'</span>
            </p>
        <div style="overflow: hidden;">
            <p style="width: 350px; float: left; line-height: normal;margin: 5px 0;">
                <span style="box-sizing: border-box; margin: 0 0; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">专业名称：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$val["professional"].'</span>
            </p>
            <p style="line-height: normal;margin: 5px 0;">
                <span style="box-sizing: border-box; margin: 0 0; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">学历/学位：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$val["edurecord"].'</span>
            </p>
        </div>
        <div style="overflow: hidden;">
            <p style="width: 350px; float: left; line-height: normal;margin: 5px 0;">
                <span style="box-sizing: border-box; margin: 0 0; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">入学时间：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$val["startdate"].'</span>
            </p>
            <p style="line-height: normal;margin: 5px 0;margin-bottom:10px;">
                <span style="box-sizing: border-box; margin: 0 0; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">毕业时间：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$val["enddate"].'</span>
            </p>
        </div>
    </ul>';
        }
        $data[0] = '
<ul class=" list-paddingleft-2" style="white-space: normal; box-sizing: border-box; padding: 0px; border-top: none; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial;  background-color: rgb(240, 240, 240);">
    <li style="list-style:none;width: 100%">
        <p style="line-height: normal;">
            &nbsp;<span style="box-sizing: border-box; margin: 0px; padding: 0px;">个人信息</span>
        </p>
    </li>
    <ul class="resume_msg_jyjl list-paddingleft-2" style="list-style-type: square;">
        <div style="overflow: hidden;">
                <p style="float: left; width: 200px; line-height: normal;margin: 5px 0;">
                    <span style="box-sizing: border-box; margin: 0px; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">姓名：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$info["name"].'</span>
                </p>
                <p style="float: left; width: 150px;; line-height: normal;margin: 5px 0;">
                    <span style=" box-sizing: border-box; margin: 0px; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">性别：</span><span style=" box-sizing: border-box; margin: 0px; padding: 0px;">'.$info["gender"].'</span>
                </p>
                <p style=" line-height: normal;margin: 5px 0;">
                    <span style=" box-sizing: border-box; margin: 0px; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">出生日期：</span><span style=" box-sizing: border-box; margin: 0px; padding: 0px;">'.$info["birthday"].'</span>
                </p>
        </div>
        <div style="overflow: hidden;">
            <p style="float:left;width: 350px; line-height: normal;margin: 5px 0px;">
                <span style="box-sizing: border-box; margin: 0px; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">户籍所在地：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$info["areaname"].'</span>
            </p>
            <p style=" line-height: normal;margin: 5px 0;">
                <span style=" box-sizing: border-box; margin: 0px; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">联系电话：</span><span style=" box-sizing: border-box; margin: 0px; padding: 0px;">'.$info["mobile"].'</span>
            </p>
        </div>
        <div style="overflow: hidden;">
            <p style="float:left; width: 350px; line-height: normal;margin: 5px 0;">
                <span style="box-sizing: border-box; margin: 0px; padding: 0px;"></span><span style=" box-sizing: border-box; margin: 0px; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">资格证类型：</span><span style=" box-sizing: border-box; margin: 0px; padding: 0px;">'.$info["cert"].'</span>
            </p>
            <p style="  line-height: normal;margin: 5px 0;">
                <span style="box-sizing: border-box; margin: 0px; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">资格证学科：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$info["cert_subject"].' </span>
            </p>
        </div>
        <p style="float: left; width: 100%; line-height: normal;margin: 5px 0;margin-bottom:10px;">
            <span style="box-sizing: border-box; margin: 0px; padding: 0px; color: rgb(160, 160, 160); display: inline-block;">自我描述：</span><span style="box-sizing: border-box; margin: 0px; padding: 0px;">'.$info["desc"].'</span>
        </p>
    </ul>
    <li style="list-style:none;width: 100%;">
        <p style="line-height: normal;">
            &nbsp;<span style="box-sizing: border-box; margin: 0px; padding: 0px;">教育经历</span>
        </p>
    </li>
    '.$data_2.'
    <li style="list-style:none;width: 100%">
        <p style="line-height: normal; margin-bottom: 5px; margin-top: 5px;">
            &nbsp;<span style="box-sizing: border-box; margin: 0px; padding: 0px;">工作经历</span>
        </p>
    </li>
    '.$data_1.'
</ul>
<p>
    <br/>
</p>';
        $data[1]=$info;
        return $data;
    }

    /**发给企业简历**/
    public static function sendMail_resume($to,$orgname,$jobtitle,$resumestr,$postname){
        if(empty($to)){
            return;
        }
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


  } 
  
      