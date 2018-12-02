<?php

namespace backend\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\product\models\TbProduct;

/**
 * TbProductSearch represents the model behind the search form about `\backend\modules\product\models\TbProduct`.
 */
class TbProductSearch extends TbProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'unit', 'author','pid','type','level'], 'integer'],
            [['name', 'remarks','createtime'], 'safe'],
            [['price'], 'number'],
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
    public function search($params,$id=null)
    {
        $query = TbProduct::find()
            ->andWhere(" type = 2 ")
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

        if ( !empty($id) ) {
            $query->andWhere("pid = {$id}");
        } else {
            $query->andWhere("pid = 0");
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'unit' => $this->unit,
            'price' => $this->price,
            'author' => $this->author,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }

    public function search_subset($params,$pid,$level)
    {
        $query = TbProduct::find()
            ->andWhere(" level = {$level} and pid = {$pid} ")
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
            'unit' => $this->unit,
            'price' => $this->price,
            'author' => $this->author,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }
}
