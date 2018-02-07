<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\tools;
use common\tools\ElasticsearchService;
use Yii;
class Elasticsearch{
    private $_service;

    function __construct($host = "", $port = "", $indexName = "", $tableName = ""){

        $config = Yii::$app->params["DB_CONFIG_ELASTICSEARCH"];

        $this->_service = new ElasticsearchService(
            $host ? $host : $config ['DB_HOST'],
            $port ? $port : $config ['DB_PORT'],
            $indexName ? $indexName : $config ['DB_INDEX'],
            $tableName ? $tableName : $config ['DB_TABLE']
        );
    }

//    private function jobJson($job,$orgname,$orgtype){
//        $searchword = $job["area_desc"].",".$job["title"].",".$orgname.",".$job["classify_desc"];
//        $job["orgname"] = $orgname;
//        $job["searchword"] = $searchword;
//        $job["orgtype"] = $orgtype;
//        unset($job["remark"]);
//        unset($job["salary_desc"]);
//        unset($job["detail"]);
//        return $job;
//    }

    private function jobJson($job,$orgname,$orgtype,$shortname){
        if(!empty($shortname)){
            $searchword = $job["area_desc"].",".$job["title"].",".$orgname.','.$shortname.",".$job["classify_desc"];
        }else{
            $searchword = $job["area_desc"].",".$job["title"].",".$orgname.",".$job["classify_desc"];
        }
        $job["short_name"] = $shortname;
        $job["orgname"] = $orgname;
        $job["searchword"] = $searchword;
        $job["orgtype"] = $orgtype;
        unset($job["remark"]);
        unset($job["salary_desc"]);
        unset($job["detail"]);
        return $job;
    }

    public function addJob($job,$orgname,$orgtype,$shortname){
        $this->_service->add($job["id"],$this->jobJson($job,$orgname,$orgtype,$shortname));
    }

    public function updJob($job,$orgname,$orgtype,$shortname){
        $this->_service->update($job["id"],$this->jobJson($job,$orgname,$orgtype,$shortname));
    }

    public function delJobById($id){
        $this->_service->delete($id);
    }

    public function delJobByIds($ids){
        foreach($ids as $id){
            $this->_service->delete($id);
        }
    }

    public function searchJobs($keyword,$area,$filters,$page = 1){
        //自然排序规则是按照发布时间、更新时间来降序排列的，
        //1，越晚发布或者刷新的越排在前面
        //2，同一天内，发布的所有都在刷新的前面
        //filter 的定义
        //key-value 的数组
        //key:certflag experience edurecord
        //value:gte:xxx,lte:xxx
        $from = ($page - 1) * 20;
        if ($from < 0){
            $from = 0;
        }
        $size = 20;
        $queryData["from"] = $from;
        $queryData["size"] = $size;
        $queryData['query']["bool"]["must"] = array();
        if (!empty($keyword)){
            array_push($queryData['query']["bool"]["must"],array("match" => array("searchword" => $keyword)));
        }
        if (!empty($area)){
            array_push($queryData['query']["bool"]["must"],array("match" => array("area_desc" => $area)));
        }
//        $queryData['query']["bool"]["must"] = array(
//            array("match" => array("name" => "孔")),
//            array("match" => array("area" => "北")),
//        );
        if (!empty($filters)){
            $queryData['query']["bool"]["filter"] = array();
            foreach($filters as $fltk => $fltv){
                array_push($queryData['query']["bool"]["filter"],array("range" => array($fltk => array("gte" => $fltv["gte"],"lte" => $fltv["lte"]))));
            }
        }
//        $queryData['query']["bool"]["filter"] = array(
//            array("range" => array("level" => array("gte" => 2,"lt" => 5))),
//            array("range" => array("id" => array("gte" => 1,"lte" => 2))),
//        );

        //$queryData['sort'] = array(
        //    "ordertime" => array("order" => "desc"),
        //    "lastupdtime" => array("order" => "desc"),
        //);
        $result = json_decode($this->_service->search($queryData),true);
        if ($result){
            return $result["hits"];
        }
        return null;
    }
}