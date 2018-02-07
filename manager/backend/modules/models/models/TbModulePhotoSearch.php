<?php

namespace backend\modules\models\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\models\models\TbModulePhoto;

/**
 * TbModulePhotoSearch represents the model behind the search form about `\backend\modules\models\models\TbModulePhoto`.
 */
class TbModulePhotoSearch extends TbModulePhoto
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'pid', 'status'], 'integer'],
            [['title', 'imgurl', 'createtime'], 'safe'],
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
    public function search($params,$pid)
    {
        $query = TbModulePhoto::find()
            ->andWhere(['pid' => $pid])
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
            'userid' => $this->userid,
            'pid' => $this->pid,
            'createtime' => $this->createtime,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'imgurl', $this->imgurl]);

        return $dataProvider;
    }
}
