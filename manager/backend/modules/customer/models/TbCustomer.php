<?php

namespace backend\modules\customer\models;

use Yii;

/**
 * This is the model class for table "tb_customer".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property integer $grade
 * @property string $createtime
 */
class TbCustomer extends \common\models\TbCustomer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(),
            [
                [['phone','name','grade'],'required'],
                ['phone', 'match', 'pattern' => '/^1[34578]\d{9}$/'],
            ]
        );
    }

    public static function get_grade()
    {
        $data = Yii::$app->db->createCommand("select * from tb_customer_discount")->queryAll();
        $result = [];
        foreach ( $data as $key => $val ) {
            $result[$val['id']] = $val['title'];
        }
        return $result;
    }


}
