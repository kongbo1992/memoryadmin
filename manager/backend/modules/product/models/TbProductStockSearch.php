<?php

namespace backend\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\product\models\TbProductStock;

/**
 * TbProductStockSearch represents the model behind the search form about `\backend\modules\product\models\TbProductStock`.
 */
class TbProductStockSearch extends TbProductStock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'total', 'stock', 'sales'], 'integer'],
            [['name'], 'safe'],
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
        $query = TbProductStock::find()
            ->select("tb_product_stock.*,tb_product.name,tb_product.unit")
            ->innerJoin('tb_product' , 'tb_product.id = tb_product_stock.product_id')
            ->where("tb_product.type = 2")
//            ->joinWith('tbProduct', true, 'inner join')
//            ->andWhere("tb_product.type = 2")
//            ->with('tbProduct')
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
            'product_id' => $this->product_id,
            'total' => $this->total,
            'stock' => $this->stock,
            'sales' => $this->sales,
        ]);

        $query->andFilterWhere(['like', 'tb_product.name', $this->name]);

        return $dataProvider;
    }
}
