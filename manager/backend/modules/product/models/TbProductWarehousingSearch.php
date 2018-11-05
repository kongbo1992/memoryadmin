<?php

namespace backend\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\product\models\TbProductWarehousing;

/**
 * TbProductWarehousingSearch represents the model behind the search form about `\backend\modules\product\models\TbProductWarehousing`.
 */
class TbProductWarehousingSearch extends TbProductWarehousing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'num', 'oper_code'], 'integer'],
            [['purchase_price', 'total'], 'number'],
            [['delivery_time', 'linkname', 'linkphone', 'channel', 'remarks', 'createtime','name'], 'safe'],
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
        $query = TbProductWarehousing::find()
            ->select("tb_product_warehousing.*,tb_product.name as name")
            ->joinWith('tbProduct', true, 'inner join')
            ->with("tbProduct")
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
            'purchase_price' => $this->purchase_price,
            'delivery_time' => $this->delivery_time,
            'total' => $this->total,
            'createtime' => $this->createtime,
            'oper_code' => $this->oper_code,
        ]);

        $query->andFilterWhere(['like', 'linkname', $this->linkname])
            ->andFilterWhere(['like', 'linkphone', $this->linkphone])
            ->andFilterWhere(['like', 'channel', $this->channel])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }
}
