<?php

namespace backend\modules\manager\behaviors;

use backend\models\LookUp;
use common\tools\DistStorage;
use Yii;
use backend\modules\manager\models\Manager;
use backend\modules\manager\models\AuthAssignment;
use yii\caching\TagDependency;
use mdm\admin\components\Configs;
use yii\helpers\HtmlPurifier;
class ManagerBehavior extends \yii\base\Behavior
{
    private $_message;

    public function init()
    {
        parent::init();
        $this->_message = '';
    }

    public function events(){
        return [
            Manager::EVENT_BEFORE_VALIDATE => 'before_validate',
            Manager::EVENT_BEFORE_INSERT => 'before_insert',
            Manager::EVENT_AFTER_INSERT => 'after_insert',
            Manager::EVENT_BEFORE_UPDATE => 'before_update',
            Manager::EVENT_AFTER_UPDATE => 'after_update',
            Manager::EVENT_AFTER_FIND => 'after_find',
        ];
    }

    /**
     * 数据提交之后，验证之前，初步数据处理
     * @param $event \yii\base\Event
     */
    public function before_validate($event){
        if($this ->owner->role > Yii::$app->user->identity->role ){
            throw new \yii\web\UnauthorizedHttpException('你没有操作权限');
        }
        if(is_array($this->owner->exam_level)){
            $this->owner->exam_level = $this->array2string($this->owner->exam_level);
        }
        if(is_array($this->owner->provinces)){
            $this->owner->provinces = $this->array2string($this->owner->provinces);
        }
        if(is_array($this->owner->subject)){
            $this->owner->subject = $this->array2string($this->owner->subject);
        }
        if(is_array($this->owner->version)){
            $this->owner->version = $this->array2string($this->owner->version);
        }
        if(is_array($this->owner->interviewtype)){
            $this->owner->interviewtype = $this->array2string($this->owner->interviewtype);
        }
        $model = $event->sender;
        $this->data_init();
    }

    /**
     * 数据验证之后，插入之前，最终数据处理
     * @param $event \yii\base\Event
     */
    public function before_insert(){
        if($this->owner->role == 9000 && !empty($this->owner->phone)){
            $this->owner->setPassword(substr($this->owner->phone,-6));
        }else{
            $this->owner->setPassword("boRn_345");
        }
        $this->owner->generateAuthKey();
        $this->owner->created_at = time();
        $this->owner->updated_at = time();
    }

    public function before_update(){
        $this->owner->updated_at = time();
    }

    public function after_insert($event){
        //创建用户后自动分配权限
        $auth = new AuthAssignment();
        $auth -> user_id = (String)$this -> owner ->id;
        $auth -> item_name = LookUp::item('manager_type',$this->owner->role);
        $auth -> created_at = time();
        $auth -> save();
    }

    /**
     * 数据插入之后，关系维护
     *
     * @param $event \yii\base\Event
     */
    public function after_update($event){
        //修改用户后权限信息更新
        $old_role = $this->owner->old_attributes;
        $old_role = $old_role['role'];
        if($old_role != $this->owner->role){
            $manager = Yii::$app->authManager;
            $old_role_name = LookUp::item('manager_type',$old_role);
            $role_name = LookUp::item('manager_type',$this->owner->role);
            $item = $manager->getRole($old_role_name);
            $item = $item ?: $manager->getPermission($old_role_name);
            $manager->revoke($item, $this->owner->id);

            $item = $manager->getRole($role_name);
            $item = $item ?: $manager->getPermission($role_name);
            $manager->assign($item, $this->owner->id);
            if($manager->cache !== null){
                TagDependency::invalidate(Configs::cache(), Configs::CACHE_TAG);
            }

        }
    }

    /**
     * 数据初步处理：
     *
     */
    private function data_init(){

    }
    public function array2string($data){
        if(!empty($data)){
            if($data[0]===''){
                array_shift($data);
            }
            return implode(',', $data);
        }
        return null;
    }
    public function after_find(){

    }

    public function getMessage(){
        return $this->_message;
    }

}
