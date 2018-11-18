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
    public $name;
    public $unit;
    public $num;
    public $product_nums;
    public $product_ids;
    public $product;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules());
    }

    /*
     * 所有客户展示
     */
    public static function get_users()
    {
        $data = Yii::$app->db->createCommand("SELECT c.name,c.phone,c.id,d.title,d.discount FROM tb_customer c INNER JOIN tb_customer_discount d ON d.id = c.grade")->queryAll();
        $users = [];
        if ( !empty($data) ) {
            foreach ( $data as $key => $val ) {
                $k = $val['id']."-".$val['name']."-".$val['phone']."-".$val['discount'];
                $discount = $val['discount'] * 10;
                $v = $val['name']."-".$val['phone']."(客户类型：{$val['title']} ；折扣：{$discount}折)";
                $users[$k] = $v;
            }
        }
        return $users;
    }

    /*
     * 产品选择
     */
    public static function get_prodect()
    {
        $data = Yii::$app->db->createCommand("SELECT p.name,p.price,s.stock,p.id FROM tb_product p INNER JOIN tb_product_stock s ON s.product_id = p.id WHERE p.type = 2")->queryAll();
        $result = [];
        if ( !empty( $data ) ) {
            foreach ( $data as $key => $value ) {
                $result[$value['id'] ."-" . $value['name']  . "-" . $value['price'] ."-" . $value['stock']] = $value['name'] ."(售价:{$value['price']}/元;库存:{$value['stock']})";
            }
        }
        return $result;
    }

}
