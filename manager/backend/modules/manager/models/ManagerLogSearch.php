<?php

namespace backend\modules\manager\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ManagerLog;

/**
 * ManagerLogSearch represents the model behind the search form about `\common\models\ManagerLog`.
 */
class ManagerLogSearch extends ManagerLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'ip', 'type'], 'integer'],
            [['route', 'created_at', 'table_name'], 'safe'],
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
        $query = ManagerLog::find();

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
            'user_id' => $this->user_id,
            'ip' => $this->ip,
            'type' => $this->type,
        ]);
        $query->andFilterWhere(['between', 'created_at',$this->created_at,date('Y-m-d H:i:s',strtotime($this->created_at)+86400)]);

        $query->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'table_name', $this->table_name]);

        return $dataProvider;
    }
}
