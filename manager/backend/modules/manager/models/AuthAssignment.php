<?php

namespace backend\modules\manager\models;

use Yii;

/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property integer $created_at
 *
 * @property AuthItem $itemName
 */
class AuthAssignment extends \common\models\AuthAssignment
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules());
    }

}
