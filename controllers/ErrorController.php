<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class ErrorController extends Controller {
  /**
   * Displays homepage.
   *
   * @return string
   */
  public function actionIndex() {
		$req = \Yii::$app->request;
    return $this->render('/site/error');
  }

	public function actionBlock() {
    return $this->render('/site/error', ['name' => 'Warning','message' => 'Your account has been blocked']);
  }
	
	public function actionRestricted() {
    return $this->render('/site/error', ['name' => 'Restricted Area','message' => "You don't have priviledge to access this page"]);
  }

	public function actionDb() {
    return $this->render('/site/error', [
			'name' => 'Warning',
			'message' => 'Error Database Connection',
			'redirect'=> \yii\helpers\Url::previous('actions-redirect')
		]);
  }
	
	public function actionBrowserNotSupport() {
    return $this->renderPartial('/site/BrowserNotSupport');
  }
}
