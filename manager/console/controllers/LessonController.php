<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 14:20
 */

namespace console\controllers;
use backend\models\LookUp;
use backend\models\Manager;
use backend\modules\lessons\models\ClassChapter;
use common\helpers\ClassRedis;
use common\models\AuthAssignment;
use common\models\ClassList;
use common\models\Hosts;
use common\models\ZOrder;
use common\tools\DistStorage;
use Yii;
use yii\console\Controller;
use common\helpers\ArrayHelper;
use com_eeo_api\EeoApi;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LessonController extends Controller{

    public function actionClassInit(){
        ini_set('max_execution_time', 0);
        //echo "begin now \n";
        //$this->gengxin_app_v2();
        //$this->gengxin_v3();
        //$this->gengxin_app_v2_single();
        //$this->gengxin_v3_single();
        $cmds = array(1,2,3);
        foreach($cmds as $cmd){
            $pid=pcntl_fork();
            if($pid==-1){ //进程创建失败
                die('fork child process failure!');
            }
            else if($pid){ //父进程处理逻辑
                pcntl_wait($status,WNOHANG);
            }
            else{ //子进程处理逻辑
                pcntl_exec(Yii::getAlias('@wwwroot')."/yii",['lesson/class-initsingle',$cmd]);
                exit();
            }
        }
    }
    
    public function actionClassInitprovince($pid){
        $firsttime = time();

        $this->appclasslist_v2($pid,2,0);
        $this->appclasslist_v2($pid,2,1);
        $this->appclasslist_v3($pid,2);
        
        echo "over. cost ".(time() - $firsttime)." seconds \n";
    }
    
    public function actionClassInitzigezheng(){
        $firsttime = time();
        
        for($k=1;$k<=31;$k++){
            $this->appclasslist_v2($k,1,0);
            $this->appclasslist_v2($k,1,1);
        }
        $this->appclasslist_v3(0,1);   //资格证
        
        echo "over. cost ".(time() - $firsttime)." seconds \n";
    }
    
    public function actionClassInitsingle($index){
        ini_set('max_execution_time', 0);
        if ($index == 1){
            $this->appclasslist_v3(0,1);   //资格证
            $this->appclasslist_v3(0,2);   //教师招聘所有省
        }
        
        $this->initClassfunc_v2($index);
        $this->initClassfunc_v2($index,1);
        
        $this->initClassfunc_v3($index);
    }
    
    private function initClassfunc_v2($index,$ios = 0){
        $to = $index * 10;
        $from = $to - 9;
        if ($index == 3){
            $to += 1;
        }
        $firsttime = time();
        for($k=$from;$k<=$to;$k++){
            $this->appclasslist_v2($k,1,$ios);
            $this->appclasslist_v2($k,2,$ios);
            echo "[$k] inial suc. \n";
        }
        echo "[$index] over. cost ".(time() - $firsttime)." seconds \n";
    }
    
    private function appclasslist_v2($provinceid,$examtype,$ios = 0){
        $str= "APP_V2_CLASSLIST_".$provinceid."_".$examtype;
        if ($ios){
            $str= "IOS_V2_CLASSLIST_".$provinceid."_".$examtype;
        }
        $statussql = "c.ClassStatus = 1 and c.viewtype = 1 and  (c.OffSaleTime > now()  or c.OffSaleTime is null) ";
        if ($ios){
            $statussql .= " and c.price < 0.01 ";
        }
        $orderexamtype = " curexamtype desc,co.orders desc ";
        if ($provinceid == 0){
            $sql = "select c.*,t.saleroomcount,(case when (c.recommend = 1 or c.examtype = $examtype) THEN 1 else 0 end) curexamtype from class c inner join tb_class_orders as co on c.classid=co.classid LEFT JOIN class_temp as t on t.ClassID = c.interviewid where $statussql and co.webtype = 1 and co.province = 0 order by  $orderexamtype,c.begintime desc,c.ClassID asc ";
        }else{
            $sql = "select c.*,t.saleroomcount,(case when (c.recommend = 1 or c.examtype = $examtype) THEN 1 else 0 end) curexamtype from class c inner join tb_class_orders as co on c.classid=co.classid LEFT JOIN class_temp as t on t.ClassID = c.interviewid where $statussql and co.webtype = 1 and (co.province = ".$provinceid." or co.province = 0) order by  $orderexamtype,c.begintime desc,c.ClassID asc ";
        }
        //echo "v2 sql = ".$sql . "\n";
        
        $list = Yii::$app->db_lower->createCommand($sql)->queryAll();
        $page=1;
        $redis=DistStorage::getMainRedisConn();
        $interview_roomcount = [];//保存面试课程包的课程数  同时售卖数量
        while($c=array_shift($list)){
            if(!empty($c['interviewid']) && !empty($c['saleroomcount'])){
                if(isset($interview_roomcount[$c['interviewid']])){
                    $interview_roomcount[$c['interviewid']]++;
                }else{
                    $interview_roomcount[$c['interviewid']] = 1;
                }
                if($interview_roomcount[$c['interviewid']] > $c['saleroomcount']){
                    continue;
                }
            }
            $d[]=$c;
            if(count($d)>=10){
                $dires=$redis->hset($str,$page,json_encode($d));
                $page ++;
                $d=[];
            }
        }

        if(!empty($d)){
            $redis->hset($str,$page,json_encode($d));
            $page ++;
            $d=[];
        }

        while ($testinfo = $redis->hget($str,$page)){
            $redis->hset($str,$page,json_encode(array()));
            $page ++;
        }
    }
    
    
    private function initClassfunc_v3($index){
        $to = $index * 10;
        $from = $to - 9;
        if ($index == 3){
            $to += 1;
        }
        $firsttime = time();
        for($k=$from;$k<=$to;$k++){
            $this->appclasslist_v3($k,2);
            echo "[$k] inial suc. \n";
        }
        echo "[$index] over. cost ".(time() - $firsttime)." seconds \n";
    }
    
    private function appclasslist_v3($provinceid,$examtype){
        //rediskey格式 $settings['province']."_".$settings['exam_type']."_".$classtype."_".$examlevel."_".$examsubject;
        
        $allchannle = array(1,2,3,4); //APP_CLASSLIST_    IOS_CLASSLIST_   PC_CLASSLIST_    H5_CLASSLIST_
        
        $redis=DistStorage::getMainRedisConn();
        for($vt = 1;$vt <= 2;$vt ++){
            $statussql = "";
            if ($vt == 1){
                $statussql = "c.ClassStatus = 1 and (c.ParentID is null or c.ParentID = 0) and  (c.OffSaleTime > now()  or c.OffSaleTime is null) ";
            }else{
                $statussql = "c.ClassStatus = 1 and c.viewtype in (1,5) and  (c.OffSaleTime > now()  or c.OffSaleTime is null) ";
            }
            
            $statussql .= " and (c.examtype = $examtype or c.recommend = 1) ";

            $costatussql = " and co.webtype = 1 ";
            $str = "INIT_CLASSLIST_APP_".$provinceid."_".$examtype."_".$vt;
            foreach($allchannle as $cnl){
                $cnlstatussql = $statussql;
                $cnlcosql = $costatussql;

                if ($cnl == 1){ //APP
                    $cnlstatussql .= " and c.viewtype in (1,2) ";

                }

                if ($cnl == 2){ //IOS
                    $cnlstatussql .= " and c.price < 0.01 and c.viewtype in (1,2) ";
                    $str= "INIT_CLASSLIST_IOS_".$provinceid."_".$examtype."_".$vt;
                }

                if ($cnl == 3){ //PC
                    $cnlcosql = " and co.webtype = 2 ";
                    $str= "INIT_CLASSLIST_PC_".$provinceid."_".$examtype."_".$vt;
                }

                if ($cnl == 4){ //H5 暂时用PC的东西
                    //TODO
                    $cnlcosql = " and co.webtype = 2 ";
                    $cnlstatussql .= " and c.viewtype in (1,2)";
                    $str= "INIT_CLASSLIST_H5_".$provinceid."_".$examtype."_".$vt;
                }
                //echo "v3 sql = "."select c.* from class c inner join tb_class_orders as co on c.classid=co.classid where $statussql $costatussql and (co.province = ".$provinceid." or co.province = 0) order by  co.orders desc,c.begintime desc \n ";
                //parentid,c.classid,c.classname,c.price,c.price_str,c.starttime,c.classhour,c.teacher,c.zhibourl,c.endtime,c.picurl,c.classtype,c.classstatus,c.begintime,c.sortorder,c.examtype,c.examlevel,c.recommend,c.total,c.stock,c.salescount,c.createtime,c.onsaletime,c.offsaletime,c.stopsaletime,c.appointgroup,c.appointgroupkey,c.appointgroupkey2,c.qqgroup,c.qqgroupkey,c.qqgroupkey2,c.jisongshijian,c.classcount,c.auser,c.handout,c.subject,c.viewtype,c.interviewid
                if ($provinceid == 0){
                    if ($examtype == 1){
                        //教师资格证所有省
                        $list = Yii::$app->db_lower->createCommand("select c.*,t.saleroomcount from class c inner join tb_class_orders as co on c.classid=co.classid LEFT JOIN class_temp as t on t.ClassID = c.interviewid where $cnlstatussql $cnlcosql and co.province = 0 order by  co.orders desc,c.begintime desc ")->queryAll();
                    }else{
                        //教师招聘的所有省
                        $list = Yii::$app->db_lower->createCommand("select c.*,t.saleroomcount from class c LEFT JOIN class_temp as t on t.ClassID = c.interviewid where $cnlstatussql order by  c.sortorder desc,c.begintime desc ")->queryAll();
                    }
                }else{
                    $list = Yii::$app->db_lower->createCommand("select c.*,t.saleroomcount from class c inner join tb_class_orders as co on c.classid=co.classid LEFT JOIN class_temp as t on t.ClassID = c.interviewid where $cnlstatussql $cnlcosql and (co.province = ".$provinceid." or co.province = 0) order by  co.orders desc,c.begintime desc,c.ClassID asc ")->queryAll();
                }
                $d = array();
                $interview_roomcount = [];//保存面试课程包的课程数  同时售卖数量
                while($c=array_shift($list)){
                    unset($c["detail"]);
                    unset($c["problem"]);
                    unset($c["demo"]);
                    unset($c["teachurl"]);
                    unset($c["tutorurl"]);
                    if(!empty($c['interviewid']) && !empty($c['saleroomcount'])){
                        if(isset($interview_roomcount[$c['interviewid']])){
                            $interview_roomcount[$c['interviewid']]++;
                        }else{
                            $interview_roomcount[$c['interviewid']] = 1;
                        }
                        if($interview_roomcount[$c['interviewid']] > $c['saleroomcount']){
                            continue;
                        }
                    }
                    if ($c["viewtype"] == 2){
                        //2 面试课 3 线下课
//                        $ofclass = Yii::$app->db_lower->createCommand("select ifnull(sum(stock),0) stock,ifnull(sum(total),0) total,ifnull(sum(salescount),0) salescount from class where parentid = ".$c["classid"]." and classstatus < 2")->queryOne();
                        $ofclass = Yii::$app->db_lower->createCommand("SELECT ifnull(sum(c.stock), 0) stock,	ifnull(sum(c.total), 0) total,	ifnull(sum(c.salescount), 0) salescount FROM class_temp AS t INNER JOIN class AS c ON c.interviewid = t.ClassID WHERE t.ParentID = ".$c["classid"]." AND t.sale_status = 1")->queryOne();
                        $c["stock"] = $ofclass["stock"];
                        $c["total"] = $ofclass["total"];
                        $c["salescount"] = $ofclass["salescount"];
                    }

                    $c['teachername'] = '神秘大咖';
                    $c['teacherimgurl'] = '';
                    if (!empty($c['auser'])){
                        $teacher = Yii::$app->db_lower->createCommand("select * FROM manager WHERE id = ".$c['auser'])->queryOne();
                        $c['teachername'] = !empty($teacher['nickname'])?$teacher['nickname']:'神秘大咖';
                        $c['teacherimgurl'] = !empty($teacher['headimg'])?$teacher['headimg']:'';
                    }

                    $d[]=$c;
                }

                if(!empty($d)){
                    $redis->set($str,json_encode($d));
                }
            }
        }
        
        
        $examlevel = \yii\helpers\ArrayHelper::merge(array(0 => '全部'),Yii::$app->params['exam_level']);
        $classtype = \yii\helpers\ArrayHelper::merge(array(0 => '全部'),Yii::$app->params['class_type']);
        $examsubject = \yii\helpers\ArrayHelper::merge(array(0 => '全部'),Yii::$app->params['exam_subject']);
        foreach($classtype as $ct => $cttext){
            foreach($examlevel as $el => $eltext){
                foreach($examsubject as $es => $estext){
                    $str = "APP_CLASSLIST_".$provinceid."_".$examtype."_".$ct."_".$el."_".$es;
                    foreach($allchannle as $cnl){
                        if ($cnl == 1){ //APP
                            
                        }
                        
                        if ($cnl == 2){ //IOS
                            $str= "IOS_CLASSLIST_".$provinceid."_".$examtype."_".$ct."_".$el."_".$es;
                        }
                        
                        if ($cnl == 3){ //PC
                            $str= "PC_CLASSLIST_".$provinceid."_".$examtype."_".$ct."_".$el."_".$es;
                        }
                        
                        if ($cnl == 4){ //H5 暂时用PC的东西
                            //TODO
                            $str= "H5_CLASSLIST_".$provinceid."_".$examtype."_".$ct."_".$el."_".$es;
                        }
                        
                        $redis->del($str);
                    }
                }
            }
        }
    }
    
    private function appclasslist_v3_old($provinceid,$examtype){
        //rediskey格式 $settings['province']."_".$settings['exam_type']."_".$classtype."_".$examlevel."_".$examsubject;
        
        $allchannle = array(1,2,3); //APP_CLASSLIST_    IOS_CLASSLIST_   PC_CLASSLIST_    H5_CLASSLIST_
        
        $examlevel = \yii\helpers\ArrayHelper::merge(array(0 => '全部'),Yii::$app->params['exam_level']);
        $classtype = \yii\helpers\ArrayHelper::merge(array(0 => '全部'),Yii::$app->params['class_type']);
        $examsubject = \yii\helpers\ArrayHelper::merge(array(0 => '全部'),Yii::$app->params['exam_subject']);

        $redis=DistStorage::getMainRedisConn();

        foreach($classtype as $ct => $cttext){
            foreach($examlevel as $el => $eltext){
                foreach($examsubject as $es => $estext){
                    $statussql = "";
                    if ($ct == 0 && $el == 0 && $es == 0){
                        $statussql = "c.ClassStatus = 1 and (c.ParentID is null or c.ParentID = 0) and  (c.OffSaleTime > now()  or c.OffSaleTime is null) ";
                    }else{
                        $statussql = "c.ClassStatus = 1 and c.viewtype = 1 and  (c.OffSaleTime > now()  or c.OffSaleTime is null) ";
                    }
                    
                    $statussql .= " and (c.examtype = $examtype or c.recommend = 1) ";
                    if ($ct > 0){
                        $statussql .= "and (c.classtype = '' or c.classtype is null or FIND_IN_SET('$ct',c.classtype)) ";
                    }
                    if ($el > 0){
                        $statussql .= "and (c.examlevel = '' or c.examlevel is null or FIND_IN_SET('$el',c.examlevel)) ";
                    }
                    if ($es > 0){
                        $statussql .= "and (c.subject = '' or c.subject is null or FIND_IN_SET('$es',c.subject)) ";
                    }
                    
                    $costatussql = " and co.webtype = 1 ";
                    $str = "APP_CLASSLIST_".$provinceid."_".$examtype."_".$ct."_".$el."_".$es;
                    
                    foreach($allchannle as $cnl){
                        $cnlstatussql = $statussql;
                        $cnlcosql = $costatussql;
                        
                        if ($cnl == 1){ //APP
                            
                        }
                        
                        if ($cnl == 2){ //IOS
                            $cnlstatussql .= " and c.price < 0.01  ";
                            $str= "IOS_CLASSLIST_".$provinceid."_".$examtype."_".$ct."_".$el."_".$es;
                        }
                        
                        if ($cnl == 3){ //PC
                            $cnlcosql = " and co.webtype = 2 ";
                            $str= "PC_CLASSLIST_".$provinceid."_".$examtype."_".$ct."_".$el."_".$es;
                        }
                        
                        if ($cnl == 4){ //H5 暂时用PC的东西
                            //TODO
                            
                            //$costatussql = " and co.webtype = 2 ";
                            //$str= "H5_CLASSLIST_".$provinceid."_".$examtype."_".$ct."_".$el."_".$es;
                        }
                        //echo "v3 sql = "."select c.* from class c inner join tb_class_orders as co on c.classid=co.classid where $statussql $costatussql and (co.province = ".$provinceid." or co.province = 0) order by  co.orders desc,c.begintime desc \n ";
                        //parentid,c.classid,c.classname,c.price,c.price_str,c.starttime,c.classhour,c.teacher,c.zhibourl,c.endtime,c.picurl,c.classtype,c.classstatus,c.begintime,c.sortorder,c.examtype,c.examlevel,c.recommend,c.total,c.stock,c.salescount,c.createtime,c.onsaletime,c.offsaletime,c.stopsaletime,c.appointgroup,c.appointgroupkey,c.appointgroupkey2,c.qqgroup,c.qqgroupkey,c.qqgroupkey2,c.jisongshijian,c.classcount,c.auser,c.handout,c.subject,c.viewtype,c.interviewid
                        if ($provinceid == 0){
                            $list = Yii::$app->db_lower->createCommand("select c.* from class c inner join tb_class_orders as co on c.classid=co.classid where $cnlstatussql $cnlcosql and co.province = 0 order by  co.orders desc,c.begintime desc ")->queryAll();
                        }else{
                            $list = Yii::$app->db_lower->createCommand("select c.* from class c inner join tb_class_orders as co on c.classid=co.classid where $cnlstatussql $cnlcosql and (co.province = ".$provinceid." or co.province = 0) order by  co.orders desc,c.begintime desc ")->queryAll();
                        }
                        $page=1;

                        while($c=array_shift($list)){
                            unset($c["detail"]);
                            unset($c["problem"]);
                            unset($c["demo"]);
                            unset($c["teachurl"]);
                            unset($c["tutorurl"]);

                            if ($c["viewtype"] == 2){
                                //2 面试课 3 线下课
                                $ofclass = Yii::$app->db_lower->createCommand("select ifnull(sum(stock),0) stock,ifnull(sum(total),0) total,ifnull(sum(salescount),0) salescount from class where parentid = ".$c["classid"]." and classstatus < 2")->queryOne();
                                $c["stock"] = $ofclass["stock"];
                                $c["total"] = $ofclass["total"];
                                $c["salescount"] = $ofclass["salescount"];
                            }
                            
                            $c['teachername'] = '神秘大咖';
                            $c['teacherimgurl'] = '';
                            if (!empty($c['auser'])){
                                $teacher = Yii::$app->db_lower->createCommand("select * FROM manager WHERE id = ".$c['auser'])->queryOne();
                                $c['teachername'] = !empty($teacher['nickname'])?$teacher['nickname']:'神秘大咖';
                                $c['teacherimgurl'] = !empty($teacher['headimg'])?$teacher['headimg']:'';
                            }
                            
                            $d[]=$c;
                            if(count($d)>=10){
                                $dires=$redis->hset($str,$page,json_encode($d));
                                $page ++;
                                $d=[];
                            }
                        }

                        if(!empty($d)){
                            $redis->hset($str,$page,json_encode($d));
                            $page ++;
                            $d=[];
                        }

                        while ($testinfo = $redis->hget($str,$page)){
                            $redis->hset($str,$page,json_encode(array()));
                            $page ++;
                        }
                    }
                }
            }
        }
    }
    /**
     * tb_a_user 数据 初始化到 tb_u_user
     */
    public function actionAuserInit(){
        $teachers =Yii::$app->db->createCommand("SELECT * from tb_a_user WHERE id in(SELECT DISTINCT auser FROM class )")->queryAll();
        $model = new  Manager();
        $num = 0;
        foreach($teachers as $val){
            if(Manager::findOne($val['id'])){
                continue;
            }
            $teach_model = clone $model;
            $teach_model -> id = $val['id'];
            $teach_model -> username = $val['code'];
            $teach_model -> nickname = $val['name'];
            $teach_model -> email = $val['email']?$val['email']:$val['code'].'@52jiaoshi.com';
            $teach_model -> generateAuthKey();
            $teach_model -> setPassword($val['code'].'888888');
            $teach_model -> gender = $val['gender'];
            $teach_model -> headimg = $val['headimg'];
            $teach_model -> phone = $val['phone'];
            $teach_model -> teacherintroduction = $val['teacherintroduction'];
            $teach_model -> recommend = $val['recommend'];
            $teach_model -> intros = $val['intros'];
            $teach_model -> created_at = time();
            $teach_model -> updated_at = time();
            $teach_model -> role = $val['type'] == 3 ?10000:9000;
            $teach_model ->save(false);
            $num ++;
            echo 'complate '.$num;
        }
    }
    /**
     * 权限初始化
     */
    public function actionAuthInit(){
        $user = Manager::find()->all();
        $roles = LookUp::items('manager_type');
        $num = 0;
        foreach($user as $value){
            if(isset($roles[$value->role])){
                $curr_role = $roles[$value->role];
                if(!AuthAssignment::find()->where(['user_id'=>$value->id,'item_name'=>$curr_role])->one()){
                    $model = new AuthAssignment();
                    $model -> user_id = $value->id;
                    $model -> item_name = $curr_role;
                    $model -> created_at = time();
                    if($model -> save(false)){
                        $num ++;
                        echo 'complate '.$num;
                    }
                }
            }
        }
    }
    /**
     * 静态页刷新
     */
    public function actionStaticInit($id){
        $this->actionFixed($id);
//        $hosts = Hosts::find()->where(['status'=>0])->all();
//        if(empty($hosts)){return;}
//        $port=':8089';
//        $post_data=['class_id'=>$id,'pwd'=>base64_encode('Bornxingzhi-Born')];
//        foreach($hosts as $ht){
//            $url='http://'.$ht['ip'].$port.'/index.php/Home/File/delOne';
//            $curl = curl_init();
//            // 设置你需要抓取的URL
//            curl_setopt($curl, CURLOPT_URL, $url);
//            // 设置是否将文件头输出到浏览器，0不输出
//            curl_setopt($curl, CURLOPT_HEADER, 0);
//            // 设置cURL 参数，要求结果返回到字符串中还是输出到屏幕上。0输出屏幕并返回操作结果的BOOL值，1返回字符串
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($curl, CURLOPT_POST, 1);
//            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
//            // 运行cURL，请求网页
//            curl_exec($curl);
//            // 关闭URL请求
//            curl_close($curl);
//        }
    }
    public function actionFlushMore($ids){
        $class_ids = explode(',',$ids);
        foreach($class_ids as $class_id){
            $this->actionFixed($class_id);
        }
//        $hosts = Hosts::find()->where(['status'=>0])->all();
//        if(empty($hosts)){return;}
//        $port=':8089';
//        $post_data=['class_ids'=>$ids,'pwd'=>base64_encode('Bornxingzhi-Born')];
//        foreach($hosts as $ht){
//            $url='http://'.$ht['ip'].$port.'/index.php/Home/File/delMore';
//            $curl = curl_init();
//            // 设置你需要抓取的URL
//            curl_setopt($curl, CURLOPT_URL, $url);
//            // 设置是否将文件头输出到浏览器，0不输出
//            curl_setopt($curl, CURLOPT_HEADER, 0);
//            // 设置cURL 参数，要求结果返回到字符串中还是输出到屏幕上。0输出屏幕并返回操作结果的BOOL值，1返回字符串
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($curl, CURLOPT_POST, 1);
//            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
//            // 运行cURL，请求网页
//            curl_exec($curl);
//            // 关闭URL请求
//            curl_close($curl);
//        }
    }
    public function actionFlushStatic(){
        $hosts = Hosts::find()->where(['status'=>0])->all();
        if(empty($hosts)){return;}
        $port=':8089';
        $post_data=['pwd'=>base64_encode('Bornxingzhi-Born')];
        foreach($hosts as $ht){
            $url='http://'.$ht['ip'].$port.'/index.php/Home/File/flush_static';
//            var_dump($url);
            $curl = curl_init();
            // 设置你需要抓取的URL
            curl_setopt($curl, CURLOPT_URL, $url);
            // 设置是否将文件头输出到浏览器，0不输出
            curl_setopt($curl, CURLOPT_HEADER, 0);
            // 设置cURL 参数，要求结果返回到字符串中还是输出到屏幕上。0输出屏幕并返回操作结果的BOOL值，1返回字符串
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
            // 运行cURL，请求网页
            curl_exec($curl);
            // 关闭URL请求
            curl_close($curl);
        }
    }
    /**
     * 修复课程 及其子课程
     */
    protected function actionFixed($id){
        $class = Yii::$app->db_lower->createCommand("SELECT * from class WHERE ClassID = $id ")->queryOne();
        if($class){
            ClassRedis::afterUpd($class['classid'], $class);
            $chidren = Yii::$app->db_lower->createCommand("SELECT * from class_chapter WHERE classid = $id ")->queryAll();
            if($chidren){
                $num = 0;
                $mainredis = DistStorage::getMainRedisConn();
                foreach($chidren as $child){
                    if(!empty($child['zhibourl'])){
                        $package = $mainredis->sMembers("RM_".$child['zhibourl']);
                        if($package){
                            $status = $mainredis->hget("CLASS_CHAPTER_".$package[0],"packagestatus");
                            if($status == 2 && $child['packagestatus'] != 2){
                                $child['packagestatus'] = 2;
                                Yii::$app->db->createCommand("UPDATE class_chapter SET packagestatus = 2 WHERE id = ".$child['id'])->execute();
                            }
                            if(!in_array($child['id'],$package)){
                                $mainredis->sAdd("RM_".$child['zhibourl'],$child['id']);
                            }
                        }else{
                            $mainredis->sAdd("RM_".$child['zhibourl'],$child['id']);
                        }
                    }
                    ClassRedis::afterChapterUpd($child);
                    echo ++$num."complated\r\n";
                }
            }

        }

    }
    /**
     * 迁移课
     */
    public function actionChangeClass(){
        $from = 8896;
        $to = 8900;
        $orders = ZOrder::find()->where(['ClassID'=>$from,'Status'=>2])->all();
        if($orders){
            $num = 0;
            foreach($orders as $val){
                if(Yii::$app->db->createCommand("UPDATE z_order SET  ClassID = $to WHERE ID =  ".$val->ID)->execute()){
                    $user_redis = DistStorage::getRedisConn(3,$val->userid);
                    $user_redis -> srem("USER_CLASS_".$val->userid,$from);
                    $user_redis -> sadd("USER_CLASS_".$val->userid,$to);
                    echo ++$num."complate\r\n";
                }
            }
        }

    }
    public function actionRemClass(){
        $from = 8900;
        $orders = ZOrder::find()->where(['ClassID'=>$from])->andWhere('Status <> 2')->all();
        $num = 0;
        foreach($orders as $val){
            if(!ZOrder::find()->where(['ClassID'=>$from,'Status'=>2,'userid'=>$val->userid])->one()){
                $user_redis = DistStorage::getRedisConn(3,$val->userid);
                if($user_redis->sismember("USER_CLASS_".$val->userid,$from)){
                    $user_redis->srem("USER_CLASS_".$val->userid,$from);
                    echo ++$num."complate\r\n";
                }
            }
        }

    }
    public function actionStatClass(){
        $from = 8900;
        $orders = ZOrder::find()->where(['ClassID'=>$from,'Status'=>2])->all();
        $num = 0;
        $add = 0;
        foreach($orders as $val){
            $user_redis = DistStorage::getRedisConn(3,$val->userid);
            if($user_redis->sismember("USER_CLASS_".$val->userid,$from)){
                $num++;
            }else{
                $user_redis -> sadd("USER_CLASS_".$val->userid,$from);
                $add++;
            }
        }
        echo "\r\n".count($orders)." -- $num -- $add";
    }
    
    public function actionTestEeo(){
        //10023
        //9726
        //EEO 创建小班
        $eeoapi = new EeoApi();
        $mainredis = DistStorage::getMainRedisConn();
        $classinfo = $mainredis->hGetAll("CLASS_10023");
        if (empty($classinfo["eeo_courseid"])){
            $classinfo["eeo_courseid"] = $eeoapi->createClass($classinfo["classname"]);
            if ($classinfo["eeo_courseid"]){
                $mainredis->hSet("CLASS_10023","eeo_courseid",$classinfo["eeo_courseid"]);
                //这里还可以更新下数据库
                Yii::$app->db->createCommand("update class set eeo_courseid = ".$classinfo["eeo_courseid"]." where classid = 10023")->execute();
            }
        }
        $teacher = Manager::find()->where(['id'=>"187"])->asArray()->one();
        if (empty($teacher["eeo_ssid"])){
            $teacher["eeo_ssid"] = $eeoapi->createTeacher($teacher["phone"],$teacher["nickname"], "123456");
            Yii::$app->db->createCommand("update manager set eeo_ssid = ".$teacher["eeo_ssid"]." where id = ".$teacher["id"])->execute();
        }
        
        $chapter = ClassChapter::find()->where(['id'=>"9726"])->asArray()->one();
        if (empty($chapter["eeo_classid"])){
            $eeo_classid = $eeoapi->createChapter($classinfo["eeo_courseid"], $chapter["classname"], strtotime($chapter["begintime"]), strtotime($chapter["endtime"]), $teacher["phone"],$teacher["nickname"]);
            if ($eeo_classid){
                Yii::$app->db->createCommand("update class_chapter set eeo_classid = ".$eeo_classid." where id = ".$chapter["id"])->execute();
            }
        }
        //EEO 创建小班结束
    }


    public function actionClassRevert(){
        $classes = ClassList::find()->all();
        $redis = DistStorage::getMainRedisConn();
        $num = 0;
        foreach($classes as $class){
            $status = $redis->hGet('CLASS_'.$class->ClassID,'classstatus');
            if($status!= $class->ClassStatus){
                ClassList::updateAll(['ClassStatus'=>$status],['ClassID'=>$class->ClassID]);
                echo  $num++."\r\n";
            }

        }
    }

}