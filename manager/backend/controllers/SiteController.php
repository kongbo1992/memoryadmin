<?php
namespace backend\controllers;

use backend\models\Manager;
use backend\models\ManagerUser;
use backend\models\UpdPwdForm;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','captcha'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','center','upd-pwd','update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>[
                'class' => 'yii\captcha\CaptchaAction',
                'backColor'=>0xFFFFFF,  //背景颜色
                'minLength'=>4,  //最短为4位
                'maxLength'=>4,   //是长为4位
                'transparent'=>true,  //显示为透明
                'testLimit'=>2,
                'fixedVerifyCode' => YII_ENV_TEST ? 'test' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    /**
     * 个人中心
     */
    public function actionCenter(){
        $model = Manager::findOne(Yii::$app->user->id);
        return $this->render('center', [
            'model' => $model,
        ]);
    }
    /**
     * 修改密码
     */
    public function actionUpdPwd(){
        $model = new UpdPwdForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return  ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->upd_pwd()) {
            Yii::$app->session->setFlash("success", "密码修改成功！");
            return $this->redirect(['center']);
        } else {
            return $this->renderAjax('upd-pwd', [
                'model' => $model,
            ]);
        }
    }
    /**
     * 修改个人资料
     */
    public function actionUpdate(){
        $model = ManagerUser::findOne(Yii::$app->user->id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash("success", "修改成功！");
            return $this->redirect(['center']);
        } else {
            $model -> headimg = !empty($model -> headimg)?$model -> headimg:'';
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
}
