<?php

namespace common\models;

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
 * @property integer $type
 * @property integer $status
 */
class TbModule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_module';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'type', 'status'], 'integer'],
            [['createtime'], 'safe'],
            [['title', 'imgurl'], 'string', 'max' => 255],
            [['content'], 'string', 'max' => 5000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '模块标题',
            'content' => '模块介绍',
            'userid' => '模块创建人',
            'imgurl' => '模块图片',
            'createtime' => '创建时间',
            'type' => '相册类型',
            'status' => '相册状态0正常，1删除',
        ];
    }
}
