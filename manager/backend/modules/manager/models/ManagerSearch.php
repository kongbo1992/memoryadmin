<?php

namespace backend\modules\manager\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\manager\models\Manager;

/**
 * ManagerSearch represents the model behind the search form about `backend\modules\manager\models\Manager`.
 */
class ManagerSearch extends Manager
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'role'], 'integer'],
            [['username', 'nickname', 'email','created_at', 'updated_at','role','phone'], 'safe'],
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
        $query = self::find();

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
            'role' => $this->role,

        ]);
        $this -> time($query,['created_at','updated_at']);
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
//    报表查询
    public function search1($params)
    {
        $query = self::find()
        ->where('role=20000')
        ->andWhere(['status'=>10])
        ;

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
            'role' => $this->role,

        ]);
        $this -> time($query,['created_at','updated_at']);
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
    /**
 * 时间的搜索
 *
 * @param $query , array $colmns
 *
 * @return $query
 **/
    private function time(&$query, $colmns){
        foreach ($colmns as $key => $value) {
            if($this->$value != ''){
                $query->andWhere(['between', $value,strtotime($this->$value),strtotime($this->$value)+86400]);
            }else{
                $this->$value = null;
            }
        }
        return $query;
    }
    //    面试课老师查询
    public function search2($params)
    {
        $query = self::find()
        ->where(['role' => [4000,9000,10000]])
        ->andWhere(['status'=>10])
        ;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => new \yii\data\Sort([
                'defaultOrder'=>['role'=>SORT_ASC,'id'=>SORT_DESC]
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
            'role' => $this->role,

        ]);
//        $this -> time($query,['created_at','updated_at']);
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
