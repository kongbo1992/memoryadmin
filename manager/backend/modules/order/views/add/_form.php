<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model backend\modules\order\models\TbProductOrder */
/* @var $form yii\widgets\ActiveForm */
$banner_list = <<<JS
    $(function(){

    });

    function addproduct()
    {
        var product = $("#tbproductorder-product").val();
        var num = parseInt($("#tbproductorder-num").val());
        if ( product && num ) {
            var arr = product.split('-');
            var stock = parseInt(arr[3]);
            if ( num > stock ) {
                alert("产品库存不足！")
            } else {
                var str = '<tr id="'+arr[0]+'"><td>'+ arr[1] +'</td><td>'+ arr[2] +'元</td><td>'+ arr[3] +'</td><td>'+num+'</td><td><a href="javascript:;" class="btn btn-danger btn-sm" onclick="delproduct('+ arr[0] +','+ num +')">删除</a></td></tr>';
                $(".kb").append(str);
                var product_ids = $("#tbproductorder-product_ids").val();
                var product_nums = $("#tbproductorder-product_nums").val();
                if ( product_ids ) {
                    product_ids+= ',' + arr[0];
                    product_nums+= ',' + num;
                } else {
                    product_ids = arr[0];
                    product_nums = num;
                }
                $("#tbproductorder-product_ids").val(product_ids);
                $("#tbproductorder-product_nums").val(product_nums);
            }
        } else {
            alert("请将产品和销售数量填写完整。")
        }
    }

    function delproduct(id,num)
    {
        var product_ids = $("#tbproductorder-product_ids").val();
        var product_nums = $("#tbproductorder-product_nums").val();
        var arr1 = product_ids.split(',');
        var arr2 = product_nums.split(',');
        $.each(arr1,function(index,value){
            if ( value == id ) {

            }
        });


    }



JS;
$this->registerJs($banner_list, View::POS_END, 'banner_list');

?>

<style>
    /*.field-tbproductorder-product_ids{display: none;}*/
    /*.field-tbproductorder-product_nums{display: none;}*/
</style>

<div class="tb-product-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="from-group" style="clear: both;">
        <div class="col-sm-12">
            <?=  $form->field($model, 'customer_id')->widget(Select2::classname(), [
                'data' => \backend\modules\order\models\TbProductOrder::get_users(),
                'options' => ['placeholder' => '请选择客户...']
            ])->label('客户选择（支持电话-姓名搜索）'); ?>
        </div>
    </div>

    <div class="product">
        <div class="from-group p1" style="clear: both;">
            <div class="col-sm-6">
                <?=  $form->field($model, 'product')->widget(Select2::classname(), [
                    'data' => \backend\modules\order\models\TbProductOrder::get_prodect(),
                    'options' => ['placeholder' => '请选择出售产品...']
                ])->label('产品选择（支持名称-金额搜索）'); ?>
            </div>

            <div class="col-sm-4">
                <?= $form->field($model, 'num')->textInput() ?>
            </div>

<!--            <div >-->
<!--                --><?//= $form->field($model, 'product_ids')->hiddenInput() ?>
<!--            </div>-->
<!--            <div >-->
<!--                --><?//= $form->field($model, 'product_nums')->hiddenInput() ?>
<!--            </div>-->
            <div class="col-sm-2" style="margin-top: 23px;">
                <a href="javascript:;" class="btn btn-success btn-sm" onclick="addproduct()">增加</a>
            </div>

        </div>

    </div>

    <div class="from-group" style="clear: both;">
        <table class="table table-hover kb">
            <tr>
                <th>名称</th>
                <th>单价</th>
                <th>库存</th>
                <th>购买数量</th>
                <th>操作</th>
            </tr>
        </table>
    </div>

        <?= $form->field($model, 'product_ids')->textInput() ?>
        <?= $form->field($model, 'product_nums')->textInput() ?>

    <?= $form->field($model, 'money')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'linkname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'linkphone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discount')->textInput() ?>

    <?= $form->field($model, 'createtime')->textInput() ?>

    <?= $form->field($model, 'sales_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
