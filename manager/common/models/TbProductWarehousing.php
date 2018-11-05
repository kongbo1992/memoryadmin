<?php

namespace common\models;

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
class TbProductWarehousing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_product_warehousing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'num', 'oper_code'], 'integer'],
            [['purchase_price', 'total'], 'number'],
            [['delivery_time', 'createtime'], 'safe'],
            [['linkname'], 'string', 'max' => 128],
            [['linkphone'], 'string', 'max' => 11],
            [['channel', 'remarks'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => '产品id',
            'num' => 'Num',
            'purchase_price' => '进货价格',
            'delivery_time' => '进货时间',
            'linkname' => '进货联系人',
            'linkphone' => '联系人电话',
            'channel' => '进货渠道',
            'remarks' => '备注',
            'total' => '总额',
            'createtime' => '创建时间',
            'oper_code' => '操作员id',
        ];
    }
}
