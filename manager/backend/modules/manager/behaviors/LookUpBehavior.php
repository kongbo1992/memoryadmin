<?php

namespace backend\modules\manager\behaviors;

use Yii;
use backend\modules\manager\models\LookUp;
use backend\modules\manager\models\AuthAssignment;
use yii\helpers\HtmlPurifier;
use backend\modules\manager\models\AuthItem;

class LookUpBehavior extends \yii\base\Behavior
{
    private $_message;

    public function init()
    {
        parent::init();
        $this->_message = '';
    }

    public function events(){
        return [
            LookUp::EVENT_BEFORE_VALIDATE => 'before_validate',
            LookUp::EVENT_BEFORE_INSERT => 'before_insert',
            LookUp::EVENT_AFTER_INSERT => 'after_insert',
            LookUp::EVENT_BEFORE_UPDATE => 'before_update',
            LookUp::EVENT_AFTER_UPDATE => 'after_update',
            LookUp::EVENT_AFTER_FIND => 'after_find',
        ];
    }

    /**
     * 数据提交之后，验证之前，初步数据处理
     * @param $event \yii\base\Event
     */
    public function before_validate($event){
        $model = $event->sender;
        $this->data_init();
    }

    /**
     * 数据验证之后，插入之前，最终数据处理
     * @param $event \yii\base\Event
     */
    public function before_insert(){

        $this->owner->name = HtmlPurifier::process($this->owner->name);
        $this->owner->type = HtmlPurifier::process($this->owner->type);
    }

    public function before_update(){
        $this->owner->name = HtmlPurifier::process($this->owner->name);
        $this->owner->type = HtmlPurifier::process($this->owner->type);
    }

    public function after_insert($event){
        if($this->owner->type == 'manager_type'){
            $auth = new AuthItem();
            $auth -> name = $this->owner -> name;
            $auth -> type = 1;
            $auth -> updated_at = time();
            $auth -> save(false);
        }
    }

    /**
     * 数据插入之后，关系维护
     *
     * @param $event \yii\base\Event
     */
    public function after_update($event){
        if($this->owner->type == 'manager_type'){
            if($this->owner->old_model['name'] != $this->owner -> name){
                $auth = AuthItem::find()
                    ->where(['name'=>$this->owner->old_model['name'],'type'=>1])
                    ->one();
                $auth -> name = $this->owner -> name;
                $auth -> updated_at = time();
                if($auth -> save(false)){
                    AuthAssignment::updateAll(['item_name'=>$this->owner -> name],['item_name'=>$this->owner->old_model['name']]);
                }
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
