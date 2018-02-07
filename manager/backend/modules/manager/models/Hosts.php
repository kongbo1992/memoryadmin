<?php

namespace backend\modules\manager\models;

use Yii;

class Hosts extends \common\models\Hosts
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            [['ip','hostname'],'required'],
            ['ip', 'match', 'pattern' => '/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/'],
            ['ip', 'unique'],

        ]);
    }

}
