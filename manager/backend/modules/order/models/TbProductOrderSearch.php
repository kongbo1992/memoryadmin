<?php

namespace backend\modules\order\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\order\models\TbProductOrder;

/**
 * TbProductOrderSearch represents the model behind the search form about `\backend\modules\order\models\TbProductOrder`.
 */
class TbProductOrderSearch extends TbProductOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'num','state','customer_id','auser_id','duser_id'], 'integer'],
            [['price', 'money', 'discount'], 'number'],
            [['linkname', 'linkphone', 'address', 'createtime', 'sales_time','name','unit','remarks'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TbProductOrder::find()
            ->select("tb_product_order.*,tb_product.name as name,tb_product.unit as unit")
            ->innerJoin("tb_product","tb_product.id = tb_product_order.product_id")
            ->innerJoin("tb_customer",'tb_customer.id = tb_product_order.customer_id')
        ;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
            'num' => $this->num,
            'price' => $this->price,
            'money' => $this->money,
            'discount' => $this->discount,
            'createtime' => $this->createtime,
            'sales_time' => $this->sales_time,
        ]);

        $query->andFilterWhere(['like', 'linkname', $this->linkname])
            ->andFilterWhere(['like', 'linkphone', $this->linkphone])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
