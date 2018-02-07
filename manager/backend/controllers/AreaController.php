<?php
namespace backend\controllers;

use backend\models\ShopArea;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class AreaController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['city','area'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionCity()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = ShopArea::get_city_by_name($id);
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $account) {
                    $out[] = ['id' => $i, 'name' => $account];
                    if (empty($selected)) {
                        $selected = $i;
                    }
                }
                // Shows how you can preselect a value
                return ['output' => $out, 'selected'=>$selected];
            }
        }
        return ['output' => '', 'selected'=>''];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionArea()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = ShopArea::get_area_by_name($id);
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $account) {
                    $out[] = ['id' => $i, 'name' => $account];
                    if (empty($selected)) {
                        $selected = $i;
                    }
                }
                // Shows how you can preselect a value
                return ['output' => $out, 'selected'=>$selected];
            }
        }
        return ['output' => '', 'selected'=>''];
    }
}
