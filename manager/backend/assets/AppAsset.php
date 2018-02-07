<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/AdminLTE.min.css',
        'css/site.css',
    ];
    public $js = [
        'js/app.min.js','js/bootbox.js','js/my.js'
    ];
    public $jsOptions = [
        'position'=>\yii\web\View::POS_HEAD
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    /**
     * @var string|bool Choose skin color, eg. `'skin-blue'` or set `false` to disable skin loading
     * @see https://almsaeedstudio.com/themes/AdminLTE/documentation/index.html#layout
     */
    public static $skin;

    /**
     * @inheritdoc
     */
    public function init()
    {
        // Append skin color file if specified
        $all_skins = [
            'skin-red','skin-blue','skin-green','skin-purple','skin-yellow'
        ];
//        self::$skin = $all_skins[date("H")%5];
        self::$skin = 'skin-blue';

        if (self::$skin) {
            if (('_all-skins' !== self::$skin) && (strpos(self::$skin, 'skin-') !== 0)) {
                throw new Exception('Invalid skin specified');
            }

            $this->css[] = sprintf('css/skins/%s.min.css', self::$skin);
        }

        parent::init();
    }
}
