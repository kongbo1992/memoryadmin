<?php

namespace com_eeo_api;
use com_eeo_api\EeoApiConfig;
use Yii;
/**
 * Class EeoApi
 * @package com_eeo_api
 */
final class EeoApi {
    private $serverAddress;
    private $SID;
    private $secret;
    private $errors; //存贮报错信息

    function __construct() {
        if(!function_exists('curl_init')) {
            echo '[EeoAPI][Error][Please enable curl in php.ini]';
        }
        $this->serverAddress = EeoAPIConfig::$serverAddress;
        $this->SID = EeoAPIConfig::$SID;
        $this->secret = EeoAPIConfig::$secret;
    }

    /**创建用户
     * @param $telephone
     * @param $nickname
     * @param $password
     * @return int
     */
    public function createUser($telephone,$nickname,$password){
        $params = array();
        $params["telephone"] = $telephone;
        $params["nickname"] = $nickname;
        $params["password"] = $password;
        $path = "course.api.php?action=register";
        return $this->parseResult($path,$params);
    }

    /** 添加教师
     * @param $telephone
     * @param $nickname
     * @return bool
     */
    public function addTeacher($telephone,$nickname){
        $params = array();
        $params["teacherAccount"] = $telephone;
        $params["teacherName"] = $nickname;
        $path = "course.api.php?action=addTeacher";
        return $this->parseResult($path,$params);
    }

    /**创建老师
     * @param $telephone
     * @param $nickname
     * @param $password
     * @return bool
     */
    public function createTeacher($telephone,$nickname,$password){
        $result = $this->createUser($telephone, $nickname, $password);
        $params = array();
        $params["teacherAccount"] = $telephone;
        $params["teacherName"] = $nickname;
        $path = "course.api.php?action=addTeacher";
        if ($result){
            return $this->parseResult($path,$params);
        }else{
            $errors = $this->getErrors();
            if(isset($errors['error_info']['errno']) && $errors['error_info']['errno']==135){
                return $this->parseResult($path,$params);
            }
        }
        return false;
    }

    /**删除课节
     * @param $courseId 课程id
     * @param $classId 课节id
     * @return bool
     */
    public function deleteChapter($courseId,$classId){
        $params = array();
        $params["courseId"] = $courseId;
        $params["classId"] = $classId;
        $path = "course.api.php?action=delCourseClass";
        return $this->parseResult($path,$params);
    }

    /**创建课程
     * Filedata 封面
     * expiryTime 过期时间,时间戳
     * @param $courseName
     * @return bool
     */
    public function createClass($courseName){
        $params = array();
        $params["courseName"] = $courseName;
        $params["expiryTime"] = time() + 24 * 3600 * 300;
        $path = "course.api.php?action=addCourse";
        return $this->parseResult($path,$params);
    }
    /**创建课节
     * @param $courseId int 课程 id
     * @param $className string 96 单课名称
     * @param $beginTime int 上课时间
     * @param $endTime int 下课时间
     * @param $teacherAccount  string   11 老师账号(手机号)
     * @param $teacherName string 24 老师姓名
     * @return bool
     */
    public function createChapter($courseId,$className,$beginTime,$endTime,$teacherAccount,$teacherName){
        $params = array();
        $params["courseId"] = $courseId;
        $params["className"] = $className;
        $params["beginTime"] = $beginTime;
        $params["endTime"] = $endTime;
        $params["teacherAccount"] = $teacherAccount;
        $params["teacherName"] = $teacherName;
        $path = "course.api.php?action=addCourseClass";
        return $this->parseResult($path,$params);
    }
    /**设置课节可以直播 、回放
     * @param $courseId
     * @param $classid
     * @return bool
     */
    public function setChapterProperty($courseId,$classid){
        $params = array();
        $params["courseId"] = $courseId;
        $params["classJson"] = json_encode(array(
            array(
                "classid" => $classid,
                "record" => 1,
                "live" => 1,
                "replay" => 1,
            )
        ));
        $path = "course.api.php?action=setClassVideoMultiple";
        return $this->parseResult($path,$params);
    }
    /**获取回放和直播的url
     * @param $courseId
     * @param $classId
     * @return bool
     */
    public function getWebUrl($courseId,$classId){
        $params = array();
        $params["courseId"] = $courseId;
        $params["classId"] = $classId;
        $path = "course.api.php?action=getWebcastUrl";
        return $this->parseResult($path,$params);
    }


    /**获取回放和直播的流媒体链接
     * @param $courseId
     * @param $classId
     * @return bool
     */
    public function getStreamUrl($courseId,$classId){
        $params = array();
        $params["courseId"] = $courseId;
        $params["classId"] = $classId;
        $path = "course.api.php?action=getClassVideo";
        return $this->parseResult($path,$params);
    }
    /**加入课程
     * @param $courseId
     * @param $identity identity 1为学生,2为旁听
     * @param $studentAccount
     * @param $studentName
     * @return bool
     */
    public function addUserToCourse($courseId,$identity,$studentAccount,$studentName){
        $params = array();
        $params["courseId"] = $courseId;
        $params["identity"] = $identity;
        $params["studentAccount"] = $studentAccount;
        $params["studentName"] = $studentName;
        $path = "course.api.php?action=addCourseStudent";
        return $this->parseResult($path,$params);
    }
    
    /**删除课程内学生
     * @param $courseId
     * @param $identity identity 1为学生,2为旁听
     * @param $studentAccount
     * @param $studentName
     * @return bool
     */
    public function deleteUserFromCourse($courseId,$identity,$studentAccount){
        $params = array();
        $params["courseId"] = $courseId;
        $params["identity"] = $identity;
        $params["studentAccount"] = $studentAccount;
        $path = "course.api.php?action=delCourseStudent";
        return $this->parseResult($path,$params);
    }

    /**加入单节课
     * @param $courseId
     * @param $classId
     * @param $studentAccount
     * @param $studentName
     * @return bool
     */
    public function addUserToClass($courseId,$classId,$studentAccount,$studentName){
        $params = array();
        $params["courseId"] = $courseId;
        $params["classId"] = $classId;
        $params["identity"] = 1;
        $params["isRegister"] = 0;
        $params["studentJson"] = json_encode(array(
            array(
                "account" => $studentAccount,
                "name" => $studentName,
            )
        ));
        $path = "course.api.php?action=addClassStudentMultiple";
        return $this->parseResult($path,$params);
    }
    /**加入单节课 多个用户
     * @param $courseId
     * @param $classId
     * @param $students
     * @return bool
     */
    public function addUserToClassMulti($courseId,$classId,$students){
        $params = array();
        $params["courseId"] = $courseId;
        $params["classId"] = $classId;
        $params["identity"] = 1;
        $params["isRegister"] = 0;
        $params["studentJson"] = json_encode($students);
        $path = "course.api.php?action=addClassStudentMultiple";
        return $this->parseResult($path,$params);
    }

    /** 删除章节下 学生 多个
     * @param $courseId
     * @param $classId
     * @param $students
     * @return bool
     */
    public function deleteUserFromClassMulti($courseId,$classId,$students){
        $params = array();
        $params["courseId"] = $courseId;
        $params["classId"] = $classId;
        $params["identity"] = 1;
        $params["studentJson"] = json_encode($students);
        $path = "course.api.php?action=delClassStudentMultiple";
        return $this->parseResult($path,$params);
    }
    /**获取旁听的链接
     * @param $telephone
     * @param $nickname
     * @param $courseId
     * @param $classId
     * @return bool
     */
    public function getAttendUrl($telephone,$nickname,$courseId,$classId){
        $params = array();
        $params["courseId"] = $courseId;
        $params["classId"] = $classId;
        $params["identity"] = 2;
        $params["studentAccount"] = $telephone;
        $params["studentName"] = $nickname;
        $path = "course.api.php?action=addCourseStudent";
        if($this->parseResult($path,$params)){
            return $this->getRoomUrl($telephone, $courseId, $classId);
        }else{
            return false;
        }
    }

    /***
     * 获取唤起链接
     * telephone String 11 手机号
    lifeTime Int 密钥有效时长(单位:秒)(可为空)
    courseId Int 课程 ID
    classId Int 单课 ID
     * **/
    public function getRoomUrl($telephone,$courseId,$classId){
        $params = array();
        $params["courseId"] = $courseId;
        $params["classId"] = $classId;
        $params["telephone"] = $telephone;
        $path = "course.api.php?action=getLoginLinked";
        return $this->parseResult($path,$params);
    }
    /** 编辑章节信息
     * @param $courseId
     * @param $classid
     * @param $className
     * @param $beginTime
     * @param $endTime
     * @param $teacherAccount
     * @param $teacherName
     * @return bool
     */
    public function editChapter($courseId,$classid,$className,$beginTime,$endTime,$teacherAccount,$teacherName){
        $params = array();
        $params["courseId"] = $courseId;
        $params["classId"] = $classid;
        $params["className"] = $className;
        $params["beginTime"] = $beginTime;
        $params["endTime"] = $endTime;
        $params["teacherAccount"] = $teacherAccount;
        $params["teacherName"] = $teacherName;
        $path = "course.api.php?action=editCourseClass";
        return $this->parseResult($path,$params);
    }

    /**读取错误信息
     * @return mixed
     */
    public function getErrors(){
        return $this->errors;
    }

    /**post数据并解析返回结果
     * @param $path
     * @param array $params
     * @return bool
     */
    private function parseResult($path,$params = []){
        $result = $this->post($path,$this->prepareParameters($params));
        if($result){
            $result = json_decode($result,true);
            if(isset($result['error_info']['errno']) && $result['error_info']['errno']==1){
                return isset($result['data'])?$result['data']:true;
            }else{
                $this->errors = $result;
                Yii::$app->db->createCommand()->insert('jobs_eeo_error_record', [
                    'errno'=>isset($result['error_info']['errno'])?$result['error_info']['errno']:501,
                    'msg'=>isset($result['error_info']['error'])?$result['error_info']['error']:'response exception',
                    'create_time'=>date("Y-m-d H:i:s"),
                    'path' => $path,
                    'params'=>json_encode($params),
                    'error_info'=> json_encode($result)
                ])->execute();
                return false;
            }
        }else{
            $this->errors = 'no result';
            Yii::$app->db->createCommand()->insert('jobs_eeo_error_record', [
                'errno'=>501,
                'msg'=>'response exception',
                'create_time'=>date("Y-m-d H:i:s"),
                'path' => $path,
                'params'=>json_encode($params),
                'error_info'=> 'no result'
            ])->execute();
            return false;
        }
    }

    /** 设置身份信息
     * @param $params
     * @return mixed
     */
    private function prepareParameters($params) {
        $params["SID"] = $this->SID;
        $params["timeStamp"] = time();
        $sign = $this->buildSign($params);
        $params["safeKey"] = $sign;
        return $params;
    }

    /** 获取签名
     * @param $params
     * @return string
     */
    private function buildSign($params) {
//		$validParams = array();
//		foreach($params as $key => $value) {
//			if(!empty($value) && $value != "") {
//				array_push($validParams, $key . "=" . $value);
//			}
//		}
//		sort($validParams);
//		$sign = md5(implode("&", $validParams) . $this->appKey);
        $sign = md5($this->secret.$params["timeStamp"]);
        return $sign;
    }

    /** 发送请求
     * @param $path
     * @param $postFields
     * @return mixed
     */
    private function post($path, $postFields) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->serverAddress . $path);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_ENCODING , "");
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
//		if(DIRECTORY_SEPARATOR=='\\') {
//			curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__) . "\\cacert.pem");
//		} else {
//			curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
//		}
        $result=curl_exec($ch);
        if($result === false) {
            Yii::$app->db->createCommand()->insert('jobs_eeo_error_record',[
                'errno'=>500,
                'msg'=>'eeo system error',
                'create_time'=>date("Y-m-d H:i:s"),
                'path' => $path,
                'error_info'=> curl_error($ch)
            ])->execute();
//            echo '[EeoAPI][Error][curl error: ' . curl_error($ch) . ']';
        }
        curl_close($ch);
        return $result;
    }
}

