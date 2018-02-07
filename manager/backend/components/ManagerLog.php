<?php
namespace backend\components;
use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;
class ManagerLog
{
    public static function write($event)
    {
        // 排除日志表自身,没有主键的表不记录（没想到怎么记录。。每个表尽量都有主键吧，不一定非是自增id）
        if($event->sender instanceof \common\models\ManagerLog || !$event->sender->primaryKey()) {
            return;
        }
        // 显示详情有待优化,不过基本功能完整齐全
        if ($event->name == ActiveRecord::EVENT_AFTER_INSERT) {
            $type = 1 ;
            $description = "%s新增了表%s %s:%s的%s";
        } elseif($event->name == ActiveRecord::EVENT_AFTER_UPDATE) {
            $type = 2 ;
            $description = "%s修改了表%s %s:%s的%s";
        } else {
            $type = 3 ;
            $description = "%s删除了表%s %s:%s%s";
        }

        if (!empty($event->changedAttributes)) {
            $desc = '';
            foreach($event->changedAttributes as $name => $value) {
                if(is_string($value) && strlen($value) > 255){
                    $desc .= $name . ' : --编辑文本-- ,';
                }else{
                    $curr_value = $event->sender->getAttribute($name);
                    if($value !=  $curr_value){
                        $desc .= $name . ' : ' . $value . '=>' . $curr_value . ',';
                    }
                }
            }
            $desc = substr($desc, 0, -1);
        } else {
            $desc = '';
        }
        $tableName = $event->sender->tableSchema->name;
        $primary_name = $event->sender->primaryKey()[0];
        $primary_key = self::array2string($event->sender->getPrimaryKey());
        $description = sprintf($description, Yii::$app->user->identity->username, $tableName, $primary_name, $primary_key, $desc);
        $data = [
            'type' => $type,
            'route' => Url::to(),
            'table_name' => $tableName,
            'description' => $description,
            'user_id' => Yii::$app->user->id,
            'ip' => ip2long(Yii::$app->request->userIP),
            'created_at' => date("Y-m-d H:i:s")
        ];
        $model = new \common\models\ManagerLog();
        $model->setAttributes($data);
        $model->save();
    }

    private static function array2string($data){
        if(is_array($data)){
            $res = '';
            foreach($data as $key => $value){
                $res.= $key.':'.$value.' ';
            }
        }else{
            return $data;
        }
    }
}
