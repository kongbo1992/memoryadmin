<?php

namespace backend\modules\models\models;

use Yii;

/**
 * This is the model class for table "tb_module_photo".
 *
 * @property integer $id
 * @property string $title
 * @property string $imgurl
 * @property integer $userid
 * @property integer $pid
 * @property string $createtime
 * @property integer $status
 */
class TbModulePhoto extends \common\models\TbModulePhoto
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
//    public function attributeLabels()
//    {
//        return [
//            'id' => 'ID',
//            'title' => '图片标题',
//            'imgurl' => '图片路径',
//            'userid' => '上传人',
//            'pid' => '所属模块id',
//            'createtime' => '创建时间',
//            'status' => '0正常1删除',
//        ];
//    }
}
