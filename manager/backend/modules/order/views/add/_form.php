<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\View;

$calculate = Url::to("calculate");

/* @var $this yii\web\View */
/* @var $model backend\modules\order\models\TbProductOrder */
/* @var $form yii\widgets\ActiveForm */
$banner_list = <<<JS
    $(function(){
        $("#tbproductorder-customer").change(function(){
            var customer = $("#tbproductorder-customer").val();
            var arr = customer.split('-');
            $("#tbproductorder-linkname").val(arr[1]);
            $("#tbproductorder-linkphone").val(arr[2]);
            delmoney();
        });

        $("#tbproductorder-dct_type").change(function(){
            var dct_type = $("#tbproductorder-dct_type").val();
            delmoney();
            if ( dct_type == 1 || dct_type == 2 || dct_type == 3 ) {
                $(".field-tbproductorder-discount").show();
            } else {
                $(".field-tbproductorder-discount").hide();
                $(".field-tbproductorder-discount").val('');
            }
        });

    });

    function addproduct()
    {
        delmoney();
        var product = $("#tbproductorder-product").val();
        var num = parseInt($("#tbproductorder-num").val());

        if ( product && num ) {
            var arr = product.split('-');

            //判断商品是否添加过
            var product_ids = $("#tbproductorder-product_ids").val();
            var arr1 = product_ids.split(',');
            var a = false;
            $.each(arr1,function(index,value){
                if ( value == arr[0] ) {
                    a = true;
                    return false;
                }
            });
            if ( a ) {
                alert('该商品您已经添加过，请不要重复添加！')
                return false;
            }

            var stock = parseInt(arr[3]);
            if ( num > stock ) {
                alert("产品库存不足！")
            } else {
                var str = '<tr id="'+arr[0]+'"><td>'+ arr[1] +'</td><td>'+ arr[2] +'元</td><td>'+ arr[3] +'</td><td>'+num+'</td><td><a href="javascript:;" class="btn btn-danger btn-sm" onclick="delproduct('+ arr[0] +','+ num +')">删除</a></td></tr>';
                $(".kb").append(str);
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
                $("#tbproductorder-product").val('');
                $("#tbproductorder-num").val('');
            }
        } else {
            alert("请将产品和销售数量填写完整。")
        }
    }

    function delmoney()
    {
        $("#tbproductorder-money").val('');
        $("#money").val('');
    }

    function delproduct(id,num)
    {
        delmoney();
        var product_ids = $("#tbproductorder-product_ids").val();
        var product_nums = $("#tbproductorder-product_nums").val();
        var arr1 = product_ids.split(',');
        var arr2 = product_nums.split(',');
        var a = false;
        $.each(arr1,function(index,value){
            if ( value == id ) {
                arr1.splice(index,1);
                arr2.splice(index,1);
                $("#"+value).remove();
                a = true;
                return false;
            }
        });
        if ( a ) {
            product_ids = arr1.join(",");
            product_nums = arr2.join(",");
            $("#tbproductorder-product_ids").val(product_ids);
            $("#tbproductorder-product_nums").val(product_nums);
        } else  {
            alert("删除失败");
        }

    }

    function calculate()
    {
        var customer = $("#tbproductorder-customer").val();
        if ( !customer ) {
            alert("请先选择购买用户！");
            return '';
        }
        var product_ids = $("#tbproductorder-product_ids").val();
        var product_nums = $("#tbproductorder-product_nums").val();
        if ( !product_ids ) {
            alert("请先选择销售商品与数量！");
            return '';
        }
        var dct_type = $("#tbproductorder-dct_type").val();
        var discount = $("#tbproductorder-discount").val();
        $.get("$calculate",{customer:customer,product_ids:product_ids,product_nums:product_nums,dct_type:dct_type,discount:discount},function(data){
            if ( data.code == 200 ) {
                $("#tbproductorder-money").val(data.money);
                $("#money").val(data.money);
            } else {
                alert(data.msg);
            }
        })


    }



JS;
$this->registerJs($banner_list, View::POS_END, 'banner_list');

?>

<style>
    .field-tbproductorder-product_ids{display: none;}
    .field-tbproductorder-product_nums{display: none;}
    .field-tbproductorder-discount{display: none;}
</style>

<input type="hidden" id="discount1" value="1">
<div class="tb-product-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="from-group" style="clear: both;">
        <div class="col-sm-12">
            <?=  $form->field($model, 'customer')->widget(Select2::classname(), [
                'data' => \backend\modules\order\models\TbProductOrder::get_users(),
                'options' => ['placeholder' => '请选择客户...']
            ])->label('客户选择（支持电话-姓名搜索）'); ?>
        </div>
    </div>

    <div class="from-group" style="clear: both;">
        <div class="col-sm-2">
            <?= $form->field($model, 'linkname')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'linkphone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-7">
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
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
                <?= $form->field($model, 'num')->textInput()->label('销售数量') ?>
            </div>

            <div >
                <?= $form->field($model, 'product_ids')->hiddenInput() ?>
            </div>
            <div >
                <?= $form->field($model, 'product_nums')->hiddenInput() ?>
            </div>
            <div class="col-sm-2" style="margin-top: 23px;">
                <a href="javascript:;" class="btn btn-success btn-sm" onclick="addproduct()">增加</a>
            </div>

        </div>

    </div>

    <div class="from-group" style="clear: both;">
        <div class="" style="padding-right: 15px;padding-left: 15px;">
            <table class="table table-hover kb table-striped">
                <tr>
                    <th>名称</th>
                    <th>单价</th>
                    <th>库存</th>
                    <th>购买数量</th>
                    <th>操作</th>
                </tr>
            </table>
        </div>
    </div>

    <div class="from-group" style="clear: both;">
        <div class="col-sm-6">
            <?= $form->field($model, 'dct_type')->dropDownList([0 => '无特殊折扣','1' => '折上折' , '2' => '再次优惠金额','3' => '新折扣']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'discount')->textInput() ?>
        </div>
    </div>

    <div class="from-group" style="clear: both;">
        <div class="col-sm-2" style="margin-top: 23px;">
            <a href="javascript:;" class="btn btn-success btn-sm" onclick="calculate()">计算收款金额</a>
        </div>
        <div class="col-sm-2">
            <input type="text" id="money" style="width: 100%;height: 28px;margin-top: 25px;cursor:not-allowed;text-align: center;font-size: 16px;" disabled>
            <?= $form->field($model, 'money')->hiddenInput()->label(false) ?>

        </div>
    </div>

    <div class="form-group" style="clear: both;text-align: center;">
        <?= Html::submitButton($model->isNewRecord ? '生成订单' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
