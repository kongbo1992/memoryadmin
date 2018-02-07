<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 14:20
 */

namespace console\controllers;

use common\models\JobsArea;
use common\models\JobsClassify;
use common\models\JobsJob;
use common\models\JobsOrg;
use common\models\JobsOrgArea;
use common\models\JobsUserEduhistory;
use common\models\JobsUserHonor;
use common\models\JobsUserInfo;
use common\models\QdPerson;
use common\models\TbUUser;
use common\tools\DistStorage;
use Yii;
use yii\console\Controller;

class ZhaopinController extends Controller
{
    /**
     * 初始化 用户投递记录
     */
    public function actionRecordInit(){
        ini_set('max_execution_time', '0');
        $post_record = Yii::$app->db_lower->createCommand("SELECT * FROM jobs_user_job_post")->queryAll();
        $num = 0;
        if($post_record){
            foreach($post_record as $value){
                $zhaopin_redis = DistStorage::getRedisConn(101,$value['userid']);
                $zhaopin_redis->zAdd('USER_POST_RECORD_'.$value['userid'], $value['id'] ,$value['jobid']);
                $num ++;
                echo $num."complate\r\n";
            }
        }
    }

//    public function actionAreaInit(){
//        ini_set('max_execution_time', '0');
//        $jobs_orgs = Yii::$app->db_lower->createCommand("SELECT * FROM jobs_org")->queryAll();
//        if($jobs_orgs){
//            $num = 0;
//            foreach($jobs_orgs as $org){
//                if(!empty($org['org_areadesc'])){
//                    $arr = explode('-',$org['org_areadesc']);
//                    $pro = $arr[0];
//                    $city = $arr[1];
//                    $area = $arr[2];
//                    $area_ids = Yii::$app->db_lower->createCommand("SELECT * FROM jobs_area WHERE ( areaname LIKE '$pro'  AND `level` = 1) OR ( areaname LIKE  '$city' AND `level` = 2) OR ( areaname LIKE  '$area' AND `level` = 3) ORDER BY `level`")->queryAll();
//                    if($area_ids){
//                        $data = [];
//                        foreach($area_ids as $a_id){
//                            if($a_id['level'] == 1){
//                                $data['org_province'] = $a_id['id'];
//                            }elseif($a_id['level'] == 2){
//                                $data['org_city'] = $a_id['id'];
//                            }elseif($a_id['level'] == 3){
//                                $data['org_area'] = $a_id['id'];
//                            }
//                        }
//                        if($data){
//                            JobsOrg::updateAll($data,['id'=>$org['id']]);
//                            $num++;
//                            echo "org $num\r\n";
//                        }
//                    }
//
//                }
//            }
//        }
//
//        $user_info = Yii::$app->db_lower->createCommand("SELECT * FROM jobs_user_info")->queryAll();
//        if($user_info){
//            $num = 0;
//            foreach($user_info as $info){
//                if(!empty($info['areaname'])){
//                    $arr = explode(' ',$info['areaname']);
//                    $pro = $arr[0];
//                    $city = $arr[1];
//                    $area = $arr[2];
//                    $area_ids = Yii::$app->db_lower->createCommand("SELECT * FROM jobs_area WHERE ( areaname LIKE '$pro'  AND `level` = 1) OR ( areaname LIKE  '$city' AND `level` = 2) OR ( areaname LIKE  '$area' AND `level` = 3) ORDER BY `level`")->queryAll();
//                    if($area_ids){
//                        $data = [];
//                        foreach($area_ids as $a_id){
//                            if($a_id['level'] == 1){
//                                $data['provinceid'] = $a_id['id'];
//                            }elseif($a_id['level'] == 2){
//                                $data['cityid'] = $a_id['id'];
//                            }elseif($a_id['level'] == 3){
//                                $data['areaid'] = $a_id['id'];
//                            }
//                        }
//                        if($data){
//                            JobsUserInfo::updateAll($data,['id'=>$info['id']]);
//                            $num++;
//                            echo "info $num\r\n";
//                        }
//                    }
//
//                }
//            }
//        }
//        $jobs_jobs = Yii::$app->db_lower->createCommand("SELECT j.id,o.org_city FROM jobs_job AS j INNER JOIN jobs_org AS o ON j.orgid = o.id")->queryAll();
//        if($jobs_jobs){
//            $num = 0;
//            foreach($jobs_jobs as $job){
//                JobsJob::updateAll(['orgcityid'=>$job['org_city']],['id'=>$job['id']]);
//                $num++;
//                echo "job $num\r\n";
//
//            }
//        }
//
//    }

//    public function actionInfoInit(){
//        ini_set('max_execution_time', '0');
//        $info = Yii::$app->db_lower->createCommand("SELECT * FROM jobs_user_info")->queryAll();
//        $num = 0;
//        $now = date("Y-m-d H:i:s");
//        if($info){
//            foreach($info as $value){
//                $zhaopin_redis = DistStorage::getRedisConn(101,$value['userid']);
//                $value['upd_time'] = $now;
//                $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$value['userid'],$value);
//                $num ++;
//                echo "info ".$num."\r\n";
//            }
//        }
//        $num = 0;
//        $info = Yii::$app->db_lower->createCommand("SELECT * FROM jobs_user_wanted")->queryAll();
//        if($info){
//            foreach($info as $value){
//                $zhaopin_redis = DistStorage::getRedisConn(101,$value['userid']);
//                $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$value['userid'],['want'=>1]);
//                $num ++;
//                echo "want ".$num."\r\n";
//            }
//        }
//
//        $num = 0;
//        $info = Yii::$app->db_lower->createCommand("SELECT * FROM jobs_user_eduhistory")->queryAll();
//        if($info){
//            foreach($info as $value){
//                $zhaopin_redis = DistStorage::getRedisConn(101,$value['userid']);
//                $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$value['userid'],['edu'=>1]);
//                $num ++;
//                echo "edu ".$num."\r\n";
//            }
//        }
//        $num = 0;
//        $info = Yii::$app->db_lower->createCommand("SELECT * FROM jobs_user_workhistory")->queryAll();
//        if($info){
//            foreach($info as $value){
//                $zhaopin_redis = DistStorage::getRedisConn(101,$value['userid']);
//                $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$value['userid'],['exp'=>1]);
//                $num ++;
//                echo "exp ".$num."\r\n";
//            }
//        }
//        $num = 0;
//        $info = Yii::$app->db_lower->createCommand("SELECT * FROM jobs_user_certs")->queryAll();
//        if($info){
//            foreach($info as $value){
//                $zhaopin_redis = DistStorage::getRedisConn(101,$value['userid']);
//                $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$value['userid'],['cert'=>1]);
//                $num ++;
//                echo "cert ".$num."\r\n";
//            }
//        }
//    }


    public function actionInitArea(){
        ini_set('max_execution_time', '0');
        $areas = JobsArea::find()->asArray()->indexBy('id')->all();
        $num = 0;
        foreach($areas as $key => $val){
            if($val['level'] == 1){
                $search_id = $val['id'].":".$val['id'].":".$val['id'];
//                $search_name = $val['shortname'];
                $show_name = $val['shortname'];
                $full_name = $val['shortname'];
            }else if($val['level'] == 2){
                $pid = $val['parentid'];
                $p_info = $areas[$pid];
                $search_id = $pid.":".$val['id'].":".$val['id'];
//                $search_name = $val['shortname'];
                $show_name = $val['shortname'];
                $full_name = $p_info['shortname'].'-'.$val['shortname'];
            }else if($val['level'] == 3){
                $pid = $val['parentid'];
                $p_info = $areas[$pid];
                $ppid = $p_info['parentid'];
                $pp_info = $areas[$ppid];
                $search_id = $ppid.':'.$pid.":".$val['id'];
//                $search_name = $p_info['shortname'].$val['areaname'];
                $show_name = $val['areaname'];
                $full_name = $pp_info['shortname'].'-'.$p_info['shortname'].'-'.$val['areaname'];
            }
            Yii::$app->db->createCommand("UPDATE jobs_area SET search_id = '$search_id',show_name = '$show_name',full_name = '$full_name' WHERE id = $key")->execute();
            $num++;
            echo $num."complate\r\n";
        }
    }
//    public function actionInitOrgArea(){
//        ini_set('max_execution_time', '0');
//        $jobs = JobsJob::find()->all();
//        $org_area = new JobsOrgArea();
//        $num = 0;
//        foreach($jobs as $job){
//            $job_id = $job -> id;
//            $data = JobsOrgArea::find()->where(['orgid'=>$job->orgid])->one();
//            if($data){
//                $area_id = $data->id;
//                Yii::$app->db->createCommand("UPDATE jobs_job SET org_area_id = '$area_id'  WHERE id = $job_id")->execute();
//                $num++;
//                echo $num."complate\r\n";
//            }else{
//                $curr_area = clone $org_area;
//                $curr_area -> provinceid = $job -> provinceid;
//                $curr_area -> cityid = $job -> cityid;
//                $curr_area -> areaid = $job -> areaid;
//                $curr_area -> area_desc = $job -> area_desc;
//                $curr_area -> address = $job -> address_desc;
//                $curr_area -> lng = $job -> lng;
//                $curr_area -> lng = $job -> lng;
//                $curr_area -> lat = $job -> lat;
//                $curr_area -> orgid = $job -> orgid;
//                $curr_area -> create_time = date("Y-m-d H:i:s");
//                if($curr_area->save(false)){
//                    $org_area_id = $curr_area -> id;
//                    Yii::$app->db->createCommand("UPDATE jobs_job SET org_area_id = '$org_area_id'  WHERE id = $job_id")->execute();
//                    $num++;
//                    echo $num."complate\r\n";
//                }
//            }
//
//        }
//    }
//    public function actionInitClassify(){
//        ini_set('max_execution_time', '0');
//        $areas = JobsClassify::find()->asArray()->indexBy('id')->all();
//        $num = 0;
//        foreach($areas as $key => $val){
//            if($val['level'] == 1){
//                $search_id = $val['id'].":".$val['id'].":".$val['id'];
//                $search_name = $val['name'];
//                $full_name = $val['name'];
//                $show_name = $val['name'];
//            }else if($val['level'] == 2){
//                $pid = $val['pid'];
//                $search_id = $pid.":".$val['id'].":".$val['id'];
//                $search_name = $val['name'];
//                $full_name = $areas[$pid]['name'].$val['name'];
//                $show_name = $areas[$pid]['name'].'-'.$val['name'];
//
//            }else if($val['level'] == 3){
//                $pid = $val['pid'];
//                $ppid = $areas[$pid]['pid'];
//                $search_id =$ppid.':'.$pid.":".$val['id'];
//                $search_name = $areas[$pid]['name'].$val['name'];
//                $full_name = $areas[$ppid]['name'].$areas[$pid]['name'].$val['name'];
//                $show_name = $areas[$ppid]['name'].'-'.$areas[$pid]['name'].'-'.$val['name'];
//
//            }
//            Yii::$app->db->createCommand("UPDATE jobs_classify SET search_id = '$search_id' ,search_name = '$search_name',full_name = '$full_name' ,show_name = '$show_name' WHERE id = $key")->execute();
//            $num++;
//            echo $num."complate\r\n";
//
//        }
//    }
//    public function actionInitUserInfo(){
//        ini_set('max_execution_time', '0');
//        Yii::$app->db->createCommand("UPDATE jobs_user_eduhistory SET edurecord = edurecord + 1 WHERE edurecord < 5 AND   create_time IS NULL")->execute();
//        Yii::$app->db->createCommand("UPDATE jobs_user_eduhistory SET create_time = now() WHERE create_time IS NULL")->execute();
//        echo "edu complate\r\n";
//        Yii::$app->db->createCommand("UPDATE jobs_user_workhistory SET create_time = now() WHERE create_time IS NULL")->execute();
//        echo "exp complate\r\n";
//        Yii::$app->db->createCommand("UPDATE jobs_user_honor SET create_time = now() WHERE create_time IS NULL")->execute();
//        echo "honor complate\r\n";
//
//        $level = [
//            1 => 9,
//            2 => 10,
//            3 => 11,
//            4 => 12,
//        ];
//        $level_10 = [
//            1 => 24,
//            2 => 25,
//            3 => 26,
//            9 => 27,
//            10 => 28,
//            11 => 29,
//            14 => 30,
//            15 => 31,
//        ];
//        $level_11 = [
//            1	=> 32,
//            2	=> 33,
//            3	=> 34,
//            4	=> 35,
//            5	=> 36,
//            6	=> 37,
//            7	=> 38,
//            8	=> 39,
//            9	=> 40,
//            10	=> 41,
//            11	=> 42,
//            12	=> 43,
//            13	=> 44,
//            14	=> 45,
//            15	=> 46,
//        ];
//        $level_12 = [
//            1	=> 47,
//            2	=> 48,
//            3	=> 49,
//            4	=> 50,
//            5	=> 51,
//            6	=> 52,
//            7	=> 53,
//            8	=> 54,
//            9	=> 55,
//            10	=> 56,
//            11	=> 57,
//            12	=> 58,
//            13	=> 59,
//            14	=> 60,
//            15	=> 61,
//            16  => 62
//        ];
//        $userinfo = Yii::$app->db->createCommand("select i.*,u.mobile as u_mobile from jobs_user_info i INNER JOIN tb_u_user u on u.id = i.userid ")->queryAll();
//        $num = 0;
//        foreach($userinfo as $value){
//            $id = $value['id'];
//            $userid = $value['userid'];
//            $value['mobile'] = $value['u_mobile'];
//            unset($value['u_mobile']);
//            if(empty($value['create_time'])){ //创建时间
//                $value['create_time'] = date("Y-m-d H:i:s");
//            }
//            //资格证
//            if($cert = Yii::$app->db->createCommand("select * from jobs_user_certs WHERE  userid = $userid")->queryOne()){
//                if(isset($level[$cert['level']])){
//                    $user_level = $level[$cert['level']];
//                    $value['cert'] = $user_level;
//                    if($user_level == 9){
//                        $value['cert_subject'] = 0;
//                    }elseif($user_level == 10){
//                        $value['cert_subject'] = isset($level_10[$cert['subject']])?$level_10[$cert['subject']]:0;
//                    }elseif($user_level == 11){
//                        $value['cert_subject'] = isset($level_12[$cert['subject']])?$level_11[$cert['subject']]:0;
//                    }elseif($user_level == 12){
//                        $value['cert_subject'] = isset($level_12[$cert['subject']])?$level_12[$cert['subject']]:0;
//                    }
//                }
//            }
//            //期望
//            if($wanted = Yii::$app->db->createCommand("select * from jobs_user_wanted WHERE  userid = $userid")->queryOne()){
//                //工作地
//                if(!empty($wanted['citys'])){
//                    $citys = explode(',',$wanted['citys']);
//                    if($citys){
//                        foreach($citys as $city){
//                            $c = explode(':',$city);
//                            if($c && isset($c[1])){
//                                if($c[0] != $c[1]){
//                                    $c_id = $c[1];
//                                    if($area = Yii::$app->db->createCommand("select * from jobs_area WHERE  id = $c_id AND  level = 2")->queryOne()){
//                                        $value['expect_area'] = $area['id'];
//                                        $value['expect_area_desc'] = $area['shortname'];
//                                        break;
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//                //期望薪资
//                if($wanted['salary_min'] < 2000){
//                    $value['expect_salary'] = 1;
//                }elseif($wanted['salary_min'] < 4000){
//                    $value['expect_salary'] = 2;
//                }elseif($wanted['salary_min'] < 6000){
//                    $value['expect_salary'] = 3;
//                }elseif($wanted['salary_min'] < 8000){
//                    $value['expect_salary'] = 4;
//                }elseif($wanted['salary_min'] < 10000){
//                    $value['expect_salary'] = 5;
//                }elseif($wanted['salary_min'] < 15000){
//                    $value['expect_salary'] = 6;
//                }elseif($wanted['salary_min'] < 25000){
//                    $value['expect_salary'] = 7;
//                }else{
//                    $value['expect_salary'] = 8;
//                }
//                //期望职位类型 默认全职
//                $value['expect_property'] = 1;
//                //期望 职位分类
//                if(!empty($wanted['level'])){
//                    $u_level = explode(',',$wanted['level']);
//                    $user_job = [];
//                    foreach($u_level as $u_val){
//                        if(isset($level[$u_val])){
//                            $l_arr[0] = 1;
//                            if($u_val == 1){
//                                $l_arr[1] = $level[$u_val];
//                                $l_arr[2] = $level[$u_val];
//                                if(count($user_job)>=3){
//                                    break;
//                                }
//                                $user_job[] = $l_arr;
//                            }else{
//                                $l_arr[1] = $level[$u_val];
//                                if(!empty($wanted['subject'])){
//                                    $u_subject = explode(',',$wanted['subject']);
//                                    $n_key = "level_".$level[$u_val];
//                                    $n_level = $$n_key;
//                                    foreach($u_subject as $u_s){
//                                        if(isset($n_level[$u_s])){
//                                            $l_arr[2] = $n_level[$u_s];
//                                            if(count($user_job)>=3){
//                                                break;
//                                            }
//                                            $user_job[] = $l_arr;
//                                        }
//                                    }
//                                }else{
//                                    $l_arr[2] = $level[$u_val];
//                                    if(count($user_job)>=3){
//                                        break;
//                                    }
//                                    $user_job[] = $l_arr;
//                                }
//                            }
//                        }
//                    }
//                    if(!empty($user_job)){
//                        $job_string = '';
//                        foreach($user_job as $u_j){
//                            $u_j = implode(':',$u_j);
//                            $job_string .= "'$u_j',";
//                        }
//                        $job_string = rtrim($job_string,',');
//                        if($classify = Yii::$app->db->createCommand("select * from jobs_classify WHERE  search_id in ( $job_string ) ")->queryAll()){
//                            $real_cly_id = [];
//                            $real_cly_desc = [];
//                            $real_cly_search = [];
//
//                            foreach($classify as $cly){
//                                $real_cly_id[] = $cly['search_id'];
//                                $real_cly_desc[] = $cly['name'];
//                                $real_cly_search[] = $cly['search_name'];
//                            }
//                            $value['expect_classify'] = implode(',',$real_cly_id);
//                            $value['expect_classify_desc'] = implode(',',$real_cly_desc);
//                            $value['expect_classify_search'] = implode(',',$real_cly_search);
//                        }
//                    }
//                }
//            }
//            if(Yii::$app->db->createCommand()->update('jobs_user_info',$value,['id'=>$id])->execute()!==false){
//                $zhaopin_redis = DistStorage::getRedisConn(101,$userid);
//                $value['upd_time'] = date("Y-m-d H:i:s");
//                $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$userid,$value);
//                $num++;
//                echo "$num complate\r\n";
//            }
//        }
//    }

//    public function actionInitJob(){
//        ini_set('max_execution_time', '0');
//        Yii::$app->db->createCommand("INSERT INTO `jobs_org_area` ( `orgid`, `provinceid`, `cityid`, `areaid`, `area_desc`, `address`, `lng`, `lat`, `create_time`) SELECT id,org_province,org_city,org_area,org_areadesc,org_address,org_lng,org_lat,NOW() FROM jobs_org ;")->execute();
//        echo "org_area complate\r\n";
//        $level = [
//            1 => 9,
//            2 => 10,
//            3 => 11,
//            4 => 12,
//        ];
//        $level_10 = [
//            1 => 24,
//            2 => 25,
//            3 => 26,
//            9 => 27,
//            10 => 28,
//            11 => 29,
//            14 => 30,
//            15 => 31,
//        ];
//        $level_11 = [
//            1	=> 32,
//            2	=> 33,
//            3	=> 34,
//            4	=> 35,
//            5	=> 36,
//            6	=> 37,
//            7	=> 38,
//            8	=> 39,
//            9	=> 40,
//            10	=> 41,
//            11	=> 42,
//            12	=> 43,
//            13	=> 44,
//            14	=> 45,
//            15	=> 46,
//        ];
//        $level_12 = [
//            1	=> 47,
//            2	=> 48,
//            3	=> 49,
//            4	=> 50,
//            5	=> 51,
//            6	=> 52,
//            7	=> 53,
//            8	=> 54,
//            9	=> 55,
//            10	=> 56,
//            11	=> 57,
//            12	=> 58,
//            13	=> 59,
//            14	=> 60,
//            15	=> 61,
//            16  => 62
//        ];
//        $jobs = Yii::$app->db->createCommand("SELECT * from jobs_job")->queryAll();
//        $num = 0;
//        foreach($jobs as $job){
//            $flag = false;
//            $id = $job['id'];
//            if(empty($job['provinceid'])){
//                $ord_id = $job['orgid'];
//                $org = Yii::$app->db->createCommand("SELECT id,org_province,org_city,org_area,org_areadesc,org_address,org_lng,org_lat,NOW() FROM jobs_org WHERE id = $ord_id")->queryOne();
//                if($org){
//                    $job['provinceid'] = $org['org_province'];
//                    $job['cityid'] = $org['org_city'];
//                    $job['areaid'] = $org['org_area'];
//                    $job['area_desc'] = $org['org_areadesc'];
//                    $job['address_desc'] = $org['org_address'];
//                    $job['lng'] = $org['org_lng'];
//                    $job['lat'] = $org['org_lat'];
//                    $flag = true;
//                }
//            }
//            if(empty($job['classify'])){
//                if(!empty($job['level'])){
//                    if(isset($level[$job['level']])){
//                        $j_l = $level[$job['level']];
//                        $cly =  [];
//                        $cly[0] =  1;
//                        $cly[1] = $j_l ;
//                        if($j_l == 9){
//                            $cly[2] = $j_l ;
//                        }else{
//                            $j_j_s = 'level_'.$j_l;
//                            $j_s_j = $$j_j_s;
//                            if(isset($j_s_j[$job['subject']])){
//                                $cly[2] = $j_s_j[$job['subject']] ;
//                            }else{
//                                $cly[2] = $j_l ;
//                            }
//                        }
//
//                        $cly = implode(':',$cly);
//                        $classify = Yii::$app->db->createCommand("select * from jobs_classify WHERE  search_id = '$cly' ")->queryOne();
//                        if($classify){
//                            $job['classify'] = $classify['search_id'];
//                            $job['classify_desc'] = $classify['full_name'];
//                            $flag = true;
//                        }
//
//                    }
//                }
//            }
//            if(strpos($job['classify_desc'],':')){
//                $flag = true;
//                $job['classify_desc'] = implode('',explode(':',$job['classify_desc']));
//            }
//            if($flag && Yii::$app->db->createCommand()->update('jobs_job',$job,['id'=>$id])->execute()!==false){
//                $num++;
//                echo "$num complate\r\n";
//            }
//        }
//
//
//
//    }

    public function actionAreaToRedis(){
        ini_set('max_execution_time', '0');
//        $areas = JobsArea::find()->asArray()->indexBy('id')->all();
//        $num = 0;
//        foreach($areas as $key => $val){
//            if($val['level'] == 1){
////                $search_id = $val['id'].":".$val['id'].":".$val['id'];
//                $search_name = $val['shortname'];
//            }else if($val['level'] == 2){
////                $search_id = $val['parentid'].":".$val['id'].":".$val['id'];
//                $search_name = $val['shortname'];
//            }else if($val['level'] == 3){
////                $search_id = $areas[$val['parentid']]['parentid'].':'.$val['parentid'].":".$val['id'];
//                $search_name = $areas[$val['parentid']]['shortname'].'-'.$val['shortname'];
//            }
//            $now_id = $val['id'];
//            Yii::$app->db->createCommand("UPDATE jobs_area SET id = '$now_id' ,show_name = '$search_name'  WHERE id = $key")->execute();
//            $num++;
//            echo $num."complate\r\n";
//        }

        $area = Yii::$app->db->createCommand("select * from jobs_area")->queryAll();
        $main_redis = DistStorage::getMainRedisConn();
        $num = 0;
        foreach($area as $value){
            $main_redis->hMset("JOB_AREA_".$value['id'],$value);
            $num++;
            echo "$num complate\r\n";
        }
    }

    public function actionUserInfoInit(){
        $user_info = JobsUserInfo::find()->asArray()->all();
        $main_redis = DistStorage::getMainRedisConn();
        $num = 0;
        foreach($user_info as $info){
            $id = $info['id'];
            $userid = $info['userid'];
            $area = null;
            $area_desc = null;
            if(!empty($info['expect_area']) && is_numeric($info['expect_area'])){
                $area_data = $main_redis->hgetall("JOB_AREA_".$info['expect_area']);
                if(!empty($area_data) && is_array($area_data) && isset($area_data['search_id']) && isset($area_data['show_name'])){
                    $area = $area_data['search_id'];
                    $area_desc = $area_data['show_name'];
                }
            }
            $fileds = ' ';
            $fileds .= !empty($area)?" expect_area = '$area' ":" expect_area = null ";
            $fileds .= !empty($area_desc)?" , expect_area_desc = '$area_desc' ":" , expect_area_desc = null ";
            if(Yii::$app->db->createCommand("UPDATE jobs_user_info SET  $fileds  WHERE id = $id")->execute()){
                $zhaopin_redis = DistStorage::getRedisConn(101,$userid);
                $new_info = JobsUserInfo::find()->where(['id'=>$id])->asArray()->one();
                $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$userid,$new_info);
                $num++;
                echo "$num complate\r\n";
            }
//            if(!empty($info['expect_classify'])){
//                $classify = JobsClassify::find()->where(['search_id'=>explode(',',$info['expect_classify'])])->all();
//                if($classify){
//                    $now_arr = [
//                        'expect_classify'=>[],
//                        'expect_classify_desc'=>[],
//                        'expect_classify_search'=>[],
//                    ];
//                    foreach($classify as $item){
//                        $now_arr['expect_classify'][] = $item['search_id'];
//                        $now_arr['expect_classify_desc'][] = $item['show_name'];
//                        $now_arr['expect_classify_search'][] = $item['search_name'];
//                    }
//                    $arr['expect_classify'] = implode(',',$now_arr['expect_classify']);
//                    $arr['expect_classify_desc'] = implode(',',$now_arr['expect_classify_desc']);
//                    $arr['expect_classify_search'] = implode(',',$now_arr['expect_classify_search']);
//                }
//            }
        }
    }

//    public function actionZhaopinInit($sign){
//        if($sign!='qwe')return;
//        $users = TbUUser::find()->where(['mobile'=>['17710379633','18301699428','15652396421','17600142533','18301129321']])->asArray()->all();
//        $num = 0;
//        if($users){
//            $redis = DistStorage::getMainRedisConn();
//            $redis -> flushAll();
//            foreach($users as $user){
//                $redis->hMset('USER_INFO:'.$user['id'],$user);
//                $redis->hset("USER_MOBILE",$user['mobile'],$user['id']);
//                $num++;
//                echo "$num user\r\n";
//            }
//        }
//        $area = Yii::$app->db->createCommand("select * from jobs_area")->queryAll();
//        $main_redis = DistStorage::getMainRedisConn();
//        $num = 0;
//        foreach($area as $value){
//            $main_redis->hMset("JOB_AREA_".$value['id'],$value);
//            $num++;
//            echo "$num area\r\n";
//        }
//    }

    public function actionImportUser(){
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '1024M');
        $user = new TbUUser();
        $job_user = new JobsUserInfo();
        $job_user_edu = new JobsUserEduhistory();
        $honor = new JobsUserHonor();
        $main_redis = DistStorage::getMainRedisConn();
//        $qd_all_user = QdPerson::find()->where("import_state = 1 and phone is not null and code is not null AND phone REGEXP '^[1][345678][0-9]{9}$'")->limit(1000)->all();
        $num = 0;
//        $from = 0;
        $limit = 5000;
//        $to = $from+$limit;
        while($qd_all_user = QdPerson::find()->where("import_state <> 1")->limit($limit)->all()){
            foreach($qd_all_user as $qd_user){
                if(TbUUser::find()->where(['mobile'=>$qd_user->uname])->one()){
                    $qd_user->import_state = 3;
                    $qd_user->import_result = "用户已存在";
                }else{
                    $curr_user = clone $user;
                    $curr_user->mobile = $qd_user->uname;
                    $curr_user->username = $qd_user->iname;
                    $curr_user->password = $qd_user->pwd;
                    $curr_user->createtime = date("Y-m-d H:i:s");
                    $curr_user->channel = 41; //导入青岛
                    if($curr_user->save()){
                        $user_info =  TbUUser::find()->where(['id'=>$curr_user->id])->asArray()->one();
                        $userid = $user_info['id'];
                        $main_redis->hMset("USER_INFO:".$userid,$user_info);
                        $main_redis->hset("USER_MOBILE",$user_info['mobile'],$userid);
                        $curr_job_user = clone $job_user;
                        $curr_job_user->userid = $curr_user->id;
                        $curr_job_user->mobile = $qd_user->phone;
                        $curr_job_user->name = $qd_user->iname;
                        $curr_job_user->email = $qd_user->email;
                        $curr_job_user->gender = $qd_user->sex=='男'?1:2;
                        $curr_job_user->birthday = $qd_user->bday;
                        $curr_job_user->title = $qd_user->zchen=='暂无'?0:($qd_user->zchen=='初级'?1:($qd_user->zchen=='中级'?2:($qd_user->zchen=='副高级'?3:($qd_user->zchen=='正高级'?4:0))));
                        $curr_job_user->edurecord = empty($qd_user->edu)?6:($qd_user->edu=='大专'?2:($qd_user->edu=='本科'?3:($qd_user->edu=='硕士'?4:($qd_user->edu=='博士'?5:1))));
                        $curr_job_user->expect_property = empty($qd_user->jobtype)?1:($qd_user->jobtype=='全职'?1:($qd_user->edu=='兼职'?2:($qd_user->edu=='实习'?3:($qd_user->edu=='全职/兼职'?1:1))));
                        $curr_job_user->experience = $this->parse_gznum($qd_user->gznum);
                        $curr_job_user->cert = empty($qd_user->jszg)?0:($qd_user->jszg=='暂无'?0:($qd_user->jszg=='幼儿园教师资格'?9:($qd_user->jszg=='小学教师资格'?10:($qd_user->jszg=='初级中学教师资格'?11:($qd_user->jszg=='高级中学教师资格'?12:($qd_user->jszg=='中等职业学校教师资格'?1000:($qd_user->jszg=='中等职业学校实习指导教师资格'?1001:($qd_user->jszg=='高等学校教师资格'?1002:0))))))));
                        if($qd_user->hka){
                            $area = $this->parse_area($qd_user->hka);
                            $curr_job_user->provinceid = $area['pid'];
                            $curr_job_user->cityid = $area['cid'];
                            $curr_job_user->areaid = $area['aid'];
                            $curr_job_user->areaname = $area['desc'];
                        }
                        if($qd_user->gzdd){
                            $area = $this->parse_area($qd_user->gzdd);
                            $curr_job_user->expect_area = $area['id'];
                            $curr_job_user->expect_area_desc = $area['show'];
                        }
                        if($qd_user->yuex){
                            $curr_job_user->expect_salary = $this->parse_salary($qd_user->yuex);
                        }
                        if($qd_user->job){
                            $classify = $this->parse_classify($qd_user->job);
                            $curr_job_user->expect_classify = $classify['id'];
                            $curr_job_user->expect_classify_desc = $classify['desc'];
                            $curr_job_user->expect_classify_search = $classify['search'];
                        }
                        if($curr_job_user->save()){
                            $job_user_info = JobsUserInfo::find()->where(['id'=>$curr_job_user->id])->asArray()->one();
                            $zhaopin_redis = DistStorage::getRedisConn(101,$userid);
                            $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$userid,$job_user_info);
                            if(!empty($curr_job_user -> edurecord) && !empty($qd_user -> school) && $qd_user -> zye && !empty($qd_user -> edutime)){
                                $curr_edu = clone $job_user_edu;
                                $curr_edu -> edurecord = $curr_job_user -> edurecord;
                                $curr_edu -> userid = $curr_job_user -> userid;
                                $curr_edu -> orgname = $qd_user -> school;
                                $curr_edu -> professional = $qd_user -> zye;
                                $curr_edu -> enddate = $qd_user -> edutime;
                                if($curr_edu->edurecord>2){
                                    $curr_edu -> startdate = (date("Y",strtotime($curr_edu -> enddate))-3)."-09-01";
                                }else{
                                    $curr_edu -> startdate = (date("Y",strtotime($curr_edu -> enddate))-4)."-09-01";
                                }
                                $curr_edu -> create_time = date("Y-m-d H:i:s");
                                if($curr_edu->save(false)){
                                    $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$userid,['edu'=>1,'upd_time'=>date("Y-m-d H:i:s")]);
                                }
                            }
                            if(!empty($qd_user->language) && $qd_user->language!='无' && !empty($qd_user->lanlevel) && $qd_user->lanlevel!='无'){
                                $curr_honor = clone $honor;
                                $curr_honor -> userid = $curr_job_user -> userid;
                                $curr_honor -> desc = $qd_user->language.$qd_user->lanlevel;
                                $curr_honor -> create_time = date("Y-m-d H:i:s");
                                if($curr_honor->save(false)){
                                    $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$userid,['honor'=>1,'upd_time'=>date("Y-m-d H:i:s")]);
                                }
                            }
                            if(!empty($qd_user->pthua) && $qd_user->pthua!='无'){
                                $curr_honor = clone $honor;
                                $curr_honor -> userid = $curr_job_user -> userid;
                                $curr_honor -> desc = "普通话".$qd_user->pthua;
                                $curr_honor -> create_time = date("Y-m-d H:i:s");
                                if($curr_honor->save(false)){
                                    $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$userid,['honor'=>1,'upd_time'=>date("Y-m-d H:i:s")]);
                                }
                            }
                            $qd_user->import_state = 2;
                            $qd_user->import_result = "成功";
                            echo $num++."\r\n";
//                                            var_dump(11);die;
                        }else{
                            $qd_user->import_state = 3;
                            $qd_user->import_result = "保存用户信息失败".json_encode($curr_job_user->getErrors());
                        }
                    }else{
                        $qd_user->import_state = 3;
                        $qd_user->import_result = "保存用户失败".json_encode($curr_user->getErrors());
                    }

                }
                $qd_user -> save(false);
            }
//            $from+=$limit;
//            $to+=$limit;
        }
//        if($qd_all_user){
//            foreach($qd_all_user as $qd_user){
////                if(JobsUserInfo::find()->where(['mobile'=>$qd_user->phone])->one()){
////                    $qd_user->import_state = 3;
////                    $qd_user->import_result = "手机号已注册";
////                }else{
//                    $curr_user = clone $user;
//                    $curr_user->mobile = $qd_user->uname;
//                    $curr_user->username = $qd_user->iname;
////                    $curr_user->username = "用户".substr($qd_user->phone,-4);
//                    $curr_user->password = md5(md5(md5(substr($qd_user->code,-6))));
//                    $curr_user->createtime = date("Y-m-d H:i:s");
//                    $curr_user->channel = 41; //导入青岛
//                    if($curr_user->save()){
//                        $user_info =  TbUUser::find()->where(['id'=>$curr_user->id])->asArray()->one();
//                        $userid = $user_info['id'];
//                        $main_redis->hMset("USER_INFO:".$userid,$user_info);
//                        $main_redis->hset("USER_MOBILE",$user_info['mobile'],$userid);
//                        $curr_job_user = clone $job_user;
//                        $curr_job_user->userid = $curr_user->id;
////                        $curr_job_user->mobile = $curr_user->mobile;
//                        $curr_job_user->mobile = $qd_user->phone;
//                        $curr_job_user->name = $qd_user->iname;
//                        $curr_job_user->email = $qd_user->email;
//                        $curr_job_user->gender = $qd_user->sex=='男'?1:2;
//                        $curr_job_user->birthday = $qd_user->bday;
//                        $curr_job_user->title = $qd_user->zchen=='暂无'?0:($qd_user->zchen=='初级'?1:($qd_user->zchen=='中级'?2:($qd_user->zchen=='副高级'?3:($qd_user->zchen=='正高级'?4:0))));
//                        $curr_job_user->edurecord = empty($qd_user->edu)?6:($qd_user->edu=='大专'?2:($qd_user->edu=='本科'?3:($qd_user->edu=='硕士'?4:($qd_user->edu=='博士'?5:1))));
//                        $curr_job_user->expect_property = empty($qd_user->jobtype)?1:($qd_user->jobtype=='全职'?1:($qd_user->edu=='兼职'?2:($qd_user->edu=='实习'?3:($qd_user->edu=='全职/兼职'?1:1))));
//                        $curr_job_user->experience = $this->parse_gznum($qd_user->gznum);
//                        $curr_job_user->cert = empty($qd_user->jszg)?0:($qd_user->jszg=='暂无'?0:($qd_user->jszg=='幼儿园教师资格'?9:($qd_user->jszg=='小学教师资格'?10:($qd_user->jszg=='初级中学教师资格'?11:($qd_user->jszg=='高级中学教师资格'?12:($qd_user->jszg=='中等职业学校教师资格'?1000:($qd_user->jszg=='中等职业学校实习指导教师资格'?1001:($qd_user->jszg=='高等学校教师资格'?1002:0))))))));
//                        if($qd_user->hka){
//                            $area = $this->parse_area($qd_user->hka);
//                            $curr_job_user->provinceid = $area['pid'];
//                            $curr_job_user->cityid = $area['cid'];
//                            $curr_job_user->areaid = $area['aid'];
//                            $curr_job_user->areaname = $area['desc'];
//                        }
//                        if($qd_user->gzdd){
//                            $area = $this->parse_area($qd_user->gzdd);
//                            $curr_job_user->expect_area = $area['id'];
//                            $curr_job_user->expect_area_desc = $area['show'];
//                        }
//                        if($qd_user->yuex){
//                            $curr_job_user->expect_salary = $this->parse_salary($qd_user->yuex);
//                        }
//                        if($qd_user->job){
//                            $classify = $this->parse_classify($qd_user->job);
//                            $curr_job_user->expect_classify = $classify['id'];
//                            $curr_job_user->expect_classify_desc = $classify['desc'];
//                            $curr_job_user->expect_classify_search = $classify['search'];
//
//                        }
//                        if($curr_job_user->save()){
//                            $job_user_info = JobsUserInfo::find()->where(['id'=>$curr_job_user->id])->asArray()->one();
//                            $zhaopin_redis = DistStorage::getRedisConn(101,$userid);
//                            $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$userid,$job_user_info);
//
//                            if(!empty($curr_job_user -> edurecord) && !empty($qd_user -> school) && $qd_user -> zye && !empty($qd_user -> edutime)){
//                                $curr_edu = clone $job_user_edu;
//                                $curr_edu -> edurecord = $curr_job_user -> edurecord;
//                                $curr_edu -> userid = $curr_job_user -> userid;
//                                $curr_edu -> orgname = $qd_user -> school;
//                                $curr_edu -> professional = $qd_user -> zye;
//                                $curr_edu -> enddate = $qd_user -> edutime;
//                                if($curr_edu->edurecord>2){
//                                    $curr_edu -> startdate = (date("Y",strtotime($curr_edu -> enddate))-3)."-09-01";
//                                }else{
//                                    $curr_edu -> startdate = (date("Y",strtotime($curr_edu -> enddate))-4)."-09-01";
//                                }
//                                $curr_edu -> create_time = date("Y-m-d H:i:s");
//                                if($curr_edu->save(false)){
//                                    $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$userid,['edu'=>1,'upd_time'=>date("Y-m-d H:i:s")]);
//                                }
//                            }
//                            if(!empty($qd_user->language) && $qd_user->language!='无' && !empty($qd_user->lanlevel) && $qd_user->lanlevel!='无'){
//                                $curr_honor = clone $honor;
//                                $curr_honor -> userid = $curr_job_user -> userid;
//                                $curr_honor -> desc = $qd_user->language.$qd_user->lanlevel;
//                                $curr_honor -> create_time = date("Y-m-d H:i:s");
//                                if($curr_honor->save(false)){
//                                    $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$userid,['honor'=>1,'upd_time'=>date("Y-m-d H:i:s")]);
//                                }
//                            }
//                            if(!empty($qd_user->pthua) && $qd_user->pthua!='无'){
//                                $curr_honor = clone $honor;
//                                $curr_honor -> userid = $curr_job_user -> userid;
//                                $curr_honor -> desc = "普通话".$qd_user->pthua;
//                                $curr_honor -> create_time = date("Y-m-d H:i:s");
//                                if($curr_honor->save(false)){
//                                    $zhaopin_redis->hmset('USER:NORMAL:INFO:'.$userid,['honor'=>1,'upd_time'=>date("Y-m-d H:i:s")]);
//                                }
//                            }
//                            die;
//                        }else{
//                            $qd_user->import_state = 3;
//                            $qd_user->import_result = "保存用户信息失败".json_encode($curr_job_user->getErrors());
//                        }
//                    }else{
//                        $qd_user->import_state = 3;
//                        $qd_user->import_result = "保存用户失败".json_encode($curr_user->getErrors());
//                    }
//                }
////            }
//        }
    }

    private function parse_area($t){
        $data = [
            'id'=>null,
            'pid'=>null,
            'cid'=>null,
            'aid'=>null,
            'desc'=>null,
            'show'=>null
        ];
        if($t){
            $pro_pos = mb_strpos($t,'省');
            $city_pos = mb_strpos($t,'市');
            $len = mb_strlen($t);
            $pro = $city = $area = null;
            if($pro_pos !== false){
                $pro = mb_substr($t,0,$pro_pos);
                if($city_pos !== -1){
                    $city = mb_substr($t,$pro_pos+3,$city_pos);
                }
                if($len > $city_pos){
                    $area = mb_substr($t,$city_pos+3);
                }
            }elseif ($city_pos !== false ){
                $city = mb_substr($t,0,$city_pos);
                if($len > $city_pos){
                    $area = mb_substr($t,$city_pos+3);
                }
            }
            $area_data = $city_data = $pro_data = [];
            if(!empty($city) && $city_data = JobsArea::find()->where("areaname like '%$city%' and level < 3")->asArray()->limit(1)->one()){
                if(!empty($area) && $city_data){
                    $area_data = JobsArea::find()->where("areaname like '%$area%' and parentid = ".$city_data['id'])->asArray()->limit(1)->one();
                }
            }elseif(!empty($pro)){
                $pro_data = JobsArea::find()->where("areaname like '%$pro%' and level < 2")->asArray()->limit(1)->one();
            }else{
                $pro_data = JobsArea::find()->where("areaname like '%$t%' and level < 2")->asArray()->limit(1)->one();
            }
            $real_data = !empty($area_data)?$area_data:(!empty($city_data)?$city_data:(!empty($pro_data)?$pro_data:[]));
            if(!empty($real_data)){
                $ids = explode(":",$real_data['search_id']);
                $data['id'] = $real_data['search_id'];
                $data['pid'] = $ids[0];
                $data['cid'] = $ids[1];
                $data['aid'] = $ids[2];
                $data['desc'] = $real_data['full_name'];
                $data['show'] = $real_data['show_name'];
            }
        }
        return $data;
    }

    private function parse_salary($p){
        $data = 3;
        if(!empty($p)){
            if($p == '500元以上' || $p == '1000元以上' || $p == '1500元以上'){
                $data = 1;
            }elseif($p == '2000元以上' || $p == '2500元以上' || $p == '3000元以上' || $p == '3500元以上'){
                $data = 2;
            }elseif($p == '4000元以上' || $p == '4500元以上' || $p == '5000元以上' ){
                $data = 3;
            }elseif($p == '6000元以上' || $p == '7000元以上' || $p == '5000元以上' ){
                $data = 4;
            }elseif($p == '8000元以上' || $p == '9000元以上' ){
                $data = 5;
            }elseif($p == '10000元以上' ){
                $data = 6;
            }elseif($p == '20000元以上' ){
                $data = 7;
            }else{
                $data = 3;
            }
        }
        return $data;
    }
    private function parse_classify($p){
        $data = [
            'id'=>null,
            'desc'=>null,
            'search'=>null
        ];
        $n = '';
        if(!empty($p)){
            if($p == '语文教师'){
                $n = '语文';
            }elseif($p == '数学教师'){
                $n = '数学';
            }elseif($p == '英语教师'){
                $n = '英语';
            }elseif($p == '计算机教师'){
                $n = '信息技术';
            }elseif($p == '电教教师'){
//                $n = '通用技术';
            }elseif($p == '物理教师'){
                $n = '物理';
            }elseif($p == '化学教师'){
                $n = '化学';
            }elseif($p == '政治教师'){
                $n = '政治';
            }elseif($p == '历史教师'){
                $n = '历史';
            }elseif($p == '地理教师'){
                $n = '地理';
            }elseif($p == '生物教师'){
                $n = '生物';
            }elseif($p == '音乐教师'){
                $n = '音乐';
            }elseif($p == '体育教师'){
                $n = '体育';
            }elseif($p == '美术教师'){
                $n = '美术';
            }elseif($p == '专业课老师'){
//                $n = '美术';
            }elseif($p == '教育心理教师'){
//                $n = '美术';
            }elseif($p == '幼儿教师'){
                $n = '幼儿';
            }
        }
        if(!empty($n)){
            $classify = JobsClassify::find()->where("name like '%$n%'")->limit(3)->asArray()->all();
            if(!empty($classify)){
                $data['id'] = implode(',',array_column($classify,'search_id'));
                $data['desc'] = implode(',',array_column($classify,'name'));
                $data['search'] = implode(',',array_column($classify,'search_name'));
            }
        }
        return $data;
    }

    private function parse_gznum($p){
        $data = null;
        if(!empty($p)){
            if($p != '无'){
                preg_match_all('/(\d)/',$p,$num);
                if($num){
                    $num = implode('',$num[0]);
                    $string = "a".$num;
                    if($string<="a10"){
                        $data = $num+2;
                    }elseif($string<="a20"){
                        $data = 13;
                    }
                }
            }
        }
        return $data;
    }
}
