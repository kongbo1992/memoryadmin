<?php

namespace backend\modules\models\models;

use Yii;

/**
 * This is the model class for table "tb_module".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $userid
 * @property string $imgurl
 * @property string $createtime
 */
class TbModule extends \common\models\TbModule
{
    public $qiniu_img_file;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '相册标题',
            'content' => '相册介绍',
            'userid' => '相册创建人',
            'imgurl' => '相册封面',
            'createtime' => '创建时间',
            'type' => '相册类型'
        ];
    }
}
