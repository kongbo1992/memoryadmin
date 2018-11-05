<?php

namespace backend\modules\product\controllers;

use Yii;
use backend\modules\product\models\TbProductStock;
use backend\modules\product\models\TbProductStockSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StockController implements the CRUD actions for TbProductStock model.
 */
class StockController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TbProductStock models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TbProductStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TbProductStock model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TbProductStock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TbProductStock();

        if ($model->load(Yii::$app->request->post()) ) {
            $post = Yii::$app->request->post();
            $model_1 = $this->findModel($post['TbProductStock']['product_id']);
            $model_1->total = $model_1->total + $post['TbProductStock']['stock'];
            $model_1->stock = $model_1->stock + $post['TbProductStock']['stock'];
            if (  $model_1->save() ) {
                Yii::$app->db->createCommand()->insert('tb_log_stock', [
                    'createtime' => date("Y-m-d H:i:s"),
                    'add' => $post['TbProductStock']['stock'],
                    'stock' =>$model_1->stock,
                    'oper_code' => Yii::$app->user->id,
                    'oper_event' => 'add',
                    'product_id' => $model_1->product_id,
                ])->execute();
                Yii::$app->session->setFlash("success", "添加库存成功！");
            } else {
                Yii::$app->session->setFlash("error", "添加库存失败！");
            }
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TbProductStock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->product_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TbProductStock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TbProductStock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TbProductStock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TbProductStock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
