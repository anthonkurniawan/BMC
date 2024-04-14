<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends BmcController {

  /**
   * {@inheritdoc}
   */
  public function behaviors() {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'only' => ['login', 'logout', 'signup', 'index'],
        'rules' => [
          [
            'allow' => true,
            'actions' => ['login', 'signup'],
            'roles' => ['?'],
          ],
          [
            'allow' => true,
            'actions' => ['index','logout', 'error', 'x'],
            'roles' => ['@'],
          ],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function actions() {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ],
      'captcha' => [
        'class' => 'yii\captcha\CaptchaAction',
        'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
      ],
    ];
  }

  /**
   * Displays homepage.
   *
   * @return string
   */
  public function actionIndex() {
    return $this->render('index');
  }

	public function actionErrorBlock() {
    return $this->render('error', ['name' => 'Warning','message' => 'Your account has been blocked']);
  }
	
	public function x() {  echo "x";die();  //actionBrowserNotSupport
    return $this->renderFile('browserNotSupport.html');
  }

  /**
   * Displays about page.
   *
   * @return string
   */
  public function actionAbout() {
    return $this->render('about');
  }

}
