<?php
use yii\helpers\Html;
use yii\web\View;

/* @var $this \yii\web\View */
/* @var $content string */
$banner_lode = <<<JS
    // zTree Start
        var my_loading = {
            //加载遮罩动画
            show: function(){
                $(".zhezhao").show();
            },
            hide: function(){
                $(".zhezhao").hide();
            }
        }
JS;
$this->registerJs($banner_lode, View::POS_END, 'banner_lode');

if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

//    dmstr\web\AdminLteAsset::register($this);
    $skin = backend\assets\AppAsset::$skin;
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@backend/web');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="renderer" content="webkit">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition <?= $skin ?> sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <div class="zhezhao" style="display: none;background: #222D32;height: 100%;width: 100%;position: absolute;z-index: 999;opacity: 0.5;">
            <img style="position: absolute;width: 40px;left: 45%;top: 40vh;" id="bjjiazai" src="http://7xodvc.com2.z0.glb.qiniucdn.com/1484358871587984d79e1b9.gif" alt=""/>
        </div>

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
