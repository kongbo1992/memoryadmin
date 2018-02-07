<?php

namespace backend\modules\manager\models;

use Yii;

/**
 * This is the model class for table "tb_1v1_teacher".
 *
 * @property integer $id
 * @property string $provinces
 * @property string $exam_level
 * @property string $subject
 * @property string $version
 * @property integer $classid
 * @property string $createtime
 */
class Tb1v1Teacher extends \common\models\Tb1v1Teacher
{
    public function rules()
    {
        return array_merge(parent::rules());
    }
}
