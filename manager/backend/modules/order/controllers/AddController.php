<?php

namespace backend\modules\order\controllers;

use Yii;
use backend\modules\order\models\TbProductOrder;
use backend\modules\order\models\TbProductOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for TbProductOrder model.
 */
class AddController extends Controller
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
     * Lists all TbProductOrder models.
     * @return mixed
     */
//    public function actionIndex()
//    {
//        $searchModel = new TbProductOrderSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
//    }

    /**
     * Displays a single TbProductOrder model.
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
     * Creates a new TbProductOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new TbProductOrder();

        if ($model->load(Yii::$app->request->post()) ) {
            $post = Yii::$app->request->post();
            $post = $post['TbProductOrder'];
            $customer = explode('-',$post['customer']);
            $model->customer_id = $customer[0];
            $model->auser_id = Yii::$app->user->id;
            $model->createtime = date("Y-m-d H:i:s");
            $model->sales_time = date("Y-m-d H:i:s");
            if ( $model->save() ) {
                $productid = explode(',',$post['product_ids']);
                $productnum = explode(',',$post['product_nums']);
                $result = [];
                $data = Yii::$app->db->createCommand("select id,price,name,price from tb_product WHERE id in ({$post['product_ids']}) AND type = 2 ")->queryAll();
                $product = [];
                foreach ( $data as $key => $val ) {
                    $product[$val['id']] = $val;
                }
                foreach ( $productid as $key => $val ) {
                    $arr = [];
                    $arr['order_id'] = $model->id;
                    $arr['product_id'] = $val;
                    $arr['num'] = $productnum[$key];
                    $arr['price'] = $product[$val]['price'];
                    $arr['product_name'] = $product[$val]['name'];
                    $result[] = $arr;
                }
                if(!empty($result)){
                    Yii::$app->db->createCommand()->batchInsert('tb_product_order_list', array_keys($result[0]), $result)->execute();
                }
                Yii::$app->session->setFlash("success", "订单完成！");
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TbProductOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TbProductOrder model.
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
     * Finds the TbProductOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TbProductOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TbProductOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * 计算订单收款金额
     */
    public function actionCalculate($customer,$product_ids,$product_nums,$dct_type,$discount)
    {
        if ( !empty($customer) && !empty($product_ids) && !empty($product_nums)) {
            $c_data = explode('-',$customer);
            $productid = explode(',',$product_ids);
            $productnum = explode(',',$product_nums);

            if ( count($productid) == count($productnum) ) {
//                获取产品的单价
                $data = Yii::$app->db->createCommand("select id,price from tb_product WHERE id in ({$product_ids}) AND type = 2 ")->queryAll();
                $product = [];
                foreach ( $data as $key => $val ) {
                    $product[$val['id']] = $val['price'];
                }
                $money = 0;
//                计算商品不打折金额
                foreach ( $productid as $key => $val ) {
                    if ( empty( $product[$val] ) ) {
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return array(
                                'code' => 203,
                                'msg' => '您输入的商品已经下架！'
                            );
                        }
                        exit();
                    }
                    $money += $product[$val] * $productnum[$key];
                }
                $zk1 = empty($c_data[3]) ? 1 : $c_data[3];
//                $money = $money * $zk1;
                if ( !empty( $dct_type ) ) {
                    if ( $dct_type == 1 ) {
                        $zk2 = empty($discount) ? 1 : $discount;
                        $money = $money * $zk1 * $zk2;
                    } elseif ( $dct_type == 2 ) {
                        $zk2 = empty($discount) ? 0 : $discount;
                        $money = $money * $zk1 - $zk2;
                    } elseif ( $dct_type == 3 ) {
                        $zk2 = empty($discount) ? 1 : $discount;
                        $money = $money  * $zk2;
                    }
                } else {
                    $money = $money  * $zk1;
                }
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return array(
                        'code' => 200,
                        'money' => $money
                    );
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return array(
                        'code' => 202,
                        'msg' => '您输入的商品信息与数量有误！'
                    );
                }
            }
        } else {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return array(
                    'code' => 201,
                    'msg' => '您输入的参数有误！'
                );
            }
        }
    }
}
