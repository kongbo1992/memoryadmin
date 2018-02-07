<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\modules\models\models\TbModulePhoto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-module-photo-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-sm-12" >
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-12">
        <?=  $form->field($model, 'imgurl')->hiddenInput()->label('相册图片')?>
        <?= $form->field($model, 'qiniu_img_file')->widget(FileInput::className(),[
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'showRemove' => false,
                // 预览的文件
                'initialPreview' => $model -> imgurl,
                'initialPreviewAsData' => true,
                'uploadUrl' => Url::to(['/qiniu/upload-image']),
                //            'uploadAsync' => true,
                // 最少上传的文件个数限制
                'minFileCount' => 1,
                // 最多上传的文件个数限制
                'maxFileCount' => 1,
                // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                'fileActionSettings' => [
                    // 设置具体图片的查看属性为false,默认为true
                    'showZoom' => true,
                    // 设置具体图片的上传属性为true,默认为true
                    'showUpload' => false,
                    // 设置具体图片的移除属性为true,默认为true
                    'showRemove' => false,
                ],
            ],
            'pluginEvents' => [
                // 上传成功后的回调方法，需要的可查看data后再做具体操作，一般不需要设置
                "fileuploaded" => "function (event, data, id, index) {
                        $('input').each(function(){
                            if(this.name == 'TbModulePhoto[imgurl]' && this.type!='file'){
                                $(this).val(data.response);
                                $('#img_file .input-group-btn').hide()
                            };
                        });
                    }",
                "filecleared" => "function (event) {
                        $('#img_file .input-group-btn').show()
                    }",
            ],
        ])->label(false) ;?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
