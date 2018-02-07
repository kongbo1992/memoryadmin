<?php

namespace backend\modules\manager\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\manager\models\Hosts;

/**
 * HostsSearch represents the model behind the search form about `\backend\modules\manager\models\Hosts`.
 */
class HostsSearch extends Hosts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['ip', 'comment', 'hostname', 'create_time', 'update_time'], 'safe'],
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
        $query = Hosts::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => new \yii\data\Sort([
                'defaultOrder'=>['id'=>SORT_DESC]
            ])
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
            'status' => $this->status,
        ]);
        $query->andFilterWhere(['between', 'create_time', $this->create_time,date('Y-m-d H:i:s',strtotime($this->create_time)+86400)]);
        $query->andFilterWhere(['between', 'update_time', $this->update_time,date('Y-m-d H:i:s',strtotime($this->update_time)+86400)]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'hostname', $this->hostname]);

        return $dataProvider;
    }
}
