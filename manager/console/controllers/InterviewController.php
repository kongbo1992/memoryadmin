<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 14:20
 */

namespace console\controllers;
use common\helpers\ClassRedis;
use common\models\ClassChapter;
use common\models\ClassList;
use common\models\EeoTempClassChapterImp;
use common\models\Manager;
use common\tools\DistStorage;
use Yii;
use yii\console\Controller;
use common\helpers\ArrayHelper;
use com_eeo_api\EeoApi;

class InterviewController extends Controller{

    public function actionImportClass(){
        ini_set('max_execution_time', 0);
        $import_data = EeoTempClassChapterImp::find()->where(['state'=>[1]])->orderBy(['state'=>SORT_ASC])->all();
        $result = [
            'success'=>0,
            'error'=>0
        ];
        if($import_data){
            $chapter = new \backend\modules\lessons\models\ClassChapter();
            foreach($import_data as $item){
                $data = [
                    'state' => 3,
                    'result' => 'success'
                ];
                if(!empty($item -> classid) && !empty($item -> mobile) &&  $class = ClassList::find()->where(['ClassID'=>$item -> classid])->one() && $manager = Manager::find()->where(['phone'=>$item -> mobile,'role'=>[9000,10000,4000]])->one()){
                    $curr_chapter = clone $chapter;
                    $curr_chapter->attachBehavior('chapter',\backend\modules\lessons\behaviors\ClassChapterBehavior::className());
                    $curr_chapter -> classname = $item -> chaptername;
                    $curr_chapter -> begintime = $item -> begintime;
                    $curr_chapter -> endtime = $item -> endtime;
                    $curr_chapter -> classid = $item -> classid;
                    $curr_chapter -> auser = $manager -> id;
                    $curr_chapter -> roomtype = $item -> roomtype; //小班
                    if($curr_chapter->save(false)){
                        $data['state'] = 2;
                        $data['result'] = 'success';
                        $result['success'] ++;
                    }else{
                        $data['state'] = 3;
                        $data['result'] = json_encode($curr_chapter->getErrors());
                        $result['error'] ++;
                    }
                }else{
                    $data['state'] = 3;
                    $data['result'] = "params is ivalid";
                    $result['error'] ++;
                }
                EeoTempClassChapterImp::updateAll($data,['id'=>$item->id]);
            }
        }
        echo json_encode($result);
    }


    public function actionSync(){
        ini_set('max_execution_time', 0);
        $redis = DistStorage::getMainRedisConn();
        $chapters = ClassChapter::find()->asArray()->all();
        $num = 0;
        foreach($chapters as $chapter){
            $redis -> hmset('CLASS_CHAPTER_'.$chapter['id'],$chapter);
            echo ++$num."\r\n";
        }

    }

    public function actionFixChapter($chapter_id){
        $chapter = ClassChapter::find()->where(['id'=>$chapter_id])->asArray()->one();
        if($chapter){
            ClassRedis::afterChapterAdd($chapter);
        }
    }
}