<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\LookUp;
use kartik\widgets\FileInput;
use yii\helpers\Url;

$this->registerJsFile("@web/plugin/ueditor/ueditor.config.js");
$this->registerJsFile("@web/plugin/ueditor/ueditor.all.min.js");
/* @var $this yii\web\View */
/* @var $model backend\modules\news\models\TbANews */
/* @var $form yii\widgets\ActiveForm */
$js = <<<JS
$(function(){
     UE.getEditor("manageruser-teacherintroduction", {
                initialFrameHeight: 400,
                scaleEnabled: true,
                elementPathEnabled: false,
                wordCount: false
            });

})
JS;
$this->registerJs($js);
/* @var $this yii\web\View */
/* @var $model backend\modules\manager\models\Manager */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="manageruser-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nickname')->textInput() ?>

    <?= $form->field($model, 'gender')->dropDownList(['0'=>'先生','1'=>'女士']) ?>

    <?php
    //$form->field($model, 'phone')->textInput(['maxlength' => true]) ;
    ?>

    <?=  $form->field($model, 'headimg')->hiddenInput()->label('头像')?>
    <?= $form->field($model, 'qiniu_img_files')->widget(FileInput::className(),[
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'showRemove' => false,
            // 预览的文件
            'initialPreview' => $model -> headimg,
            'initialPreviewAsData' => true,
            'uploadUrl' => Url::to(['/qiniu/upload-image']),
            'uploadAsync' => true,
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
                    if(this.name == 'ManagerUser[headimg]' && this.type!='file'){
                        $(this).val(data.response);
                        $('.input-group-btn').hide()
                    };
                });
            }",
            "filecleared" => "function (event) {
                $('.input-group-btn').show()
            }",
        ],


    ])->label(false) ;?>

    <?= $form->field($model, 'intros')->textarea(['maxlength' => true,'rows'=>6]) ?>

    <div class="form-group field-manageruser-teacherintroduction">
        <label class="control-label" for="manageruser-teacherintroduction">教师介绍</label>
        <textarea id="manageruser-teacherintroduction" class="" name="ManagerUser[teacherintroduction]">
                    <?php  if(!empty($model->teacherintroduction)){ echo $model->teacherintroduction;};?>
        </textarea>
        <div class="help-block"></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(' 修 改 ', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
