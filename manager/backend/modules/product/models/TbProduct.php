<?php

namespace backend\modules\product\models;

use Yii;

/**
 * This is the model class for table "tb_product".
 *
 * @property integer $id
 * @property string $name
 * @property integer $unit
 * @property string $price
 * @property string $remarks
 * @property integer $author
 */
class TbProduct extends \common\models\TbProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(),
            [
                [['unit','name','price'],'required','on' => ['create','update']],
                [['name'],'required','on' => ['ppcreate','ppupdate']],
            ]
        );
    }

}
