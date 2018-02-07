<?php
namespace backend\controllers;

use common\helpers\ClassRedis;
//use common\models\ClassChapter;
//use common\models\ClassList;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class QiniuController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['upload-image', 'upload-file','handouts','upload-audio'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * upload action.
     *
     * @return string
     */
    public function actionUploadImage()
    {
        foreach($_FILES as $kk => $value){
            foreach($value['tmp_name'] as $key => $val){
                $info = pathinfo($value['name'][$key]);
                $file_name = uniqid(time()).'.'.$info['extension'];
                $ret = Yii::$app->qiniu->putFile($file_name, $val);
                if ($ret['code'] === 0) {
                    // 上传成功
                    $p1[] = $ret['result']['url']; // 目标文件的URL地址，如：http://[七牛域名]/img/test.jpg
                } else {
                    // 上传失败
                    $code = $ret['code']; // 错误码
                    $message = $ret['message']; // 错误信息
                }
            }
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return implode(",",$p1);

    }

    public function actionUploadImgs(){

    }

    public function actionUploadFile()
    {
        foreach($_FILES as $kk => $value){
            foreach($value['tmp_name'] as $key => $val){
                $info = pathinfo($value['name'][$key]);
                $file_name = uniqid(time()).'.'.$info['extension'];
                $ret = Yii::$app->qiniu->putFile($file_name, $val);
                if ($ret['code'] === 0) {
                    // 上传成功
                    $p1[] = $ret['result']['url']; // 目标文件的URL地址，如：http://[七牛域名]/img/test.jpg
                } else {
                    // 上传失败
                    $code = $ret['code']; // 错误码
                    $message = $ret['message']; // 错误信息
                }
            }
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return implode(",",$p1);

    }

    public function actionHandouts($id)
    {
        $model = ClassChapter::findOne($id);
        if(!$model){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        foreach($_FILES as $kk => $value){
                $info = pathinfo($value['name']);
                $file_name = uniqid(time()).'.'.$info['extension'];
                $ret = Yii::$app->qiniu->putFile($file_name, $value['tmp_name']);
                if ($ret['code'] === 0) {
                    // 上传成功
                    $p1[] = $ret['result']['url']; // 目标文件的URL地址，如：http://[七牛域名]/img/test.jpg
                    $model -> filename = $ret['result']['url'];
                    if($model -> save(false)){
                        ClassRedis::afterChapterUpd($model->attributes);
                    }
                } else {
                    // 上传失败
                    $code = $ret['code']; // 错误码
                    $message = $ret['message']; // 错误信息
                }
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return implode(",",$p1);

    }

//    上传音频
    public function actionUploadAudio()
    {
        foreach($_FILES as $kk => $value){
            foreach($value['tmp_name'] as $key => $val){
//                $pl[] = $value['size']['qiniu_file'];
                $info = pathinfo($value['name'][$key]);
                $file_name = uniqid(time()).'.'.$info['extension'];
                $ret = Yii::$app->qiniukl->putFile($file_name, $val);
                if ($ret['code'] === 0) {
                    // 上传成功
                    $p1[] = $ret['result']['url']; // 目标文件的URL地址，如：http://[七牛域名]/img/test.jpg
                } else {
                    // 上传失败
                    $code = $ret['code']; // 错误码
                    $message = $ret['message']; // 错误信息
                }
            }
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return implode(",",$p1);

    }

}
