<?php

namespace backend\modules\product\models;

use Yii;

/**
 * This is the model class for table "tb_product_warehousing".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $num
 * @property string $purchase_price
 * @property string $delivery_time
 * @property string $linkname
 * @property string $linkphone
 * @property string $channel
 * @property string $remarks
 * @property string $total
 * @property string $createtime
 * @property integer $oper_code
 */
class TbProductWarehousing extends \common\models\TbProductWarehousing
{
    public $name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules());
    }

    public function getTbProduct()
    {
        return $this->hasOne(TbProduct::className(), ['id' => 'product_id']);
    }
}
