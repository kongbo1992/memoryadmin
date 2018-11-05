<?php

namespace backend\modules\order\models;

use Yii;

/**
 * This is the model class for table "tb_product_order".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $num
 * @property string $price
 * @property string $money
 * @property string $linkname
 * @property string $linkphone
 * @property string $address
 * @property double $discount
 * @property string $createtime
 * @property string $sales_time
 */
class TbProductOrder extends \common\models\TbProductOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules());
    }

}
