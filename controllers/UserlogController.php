<?php

namespace app\controllers;

use Yii;
use app\models\Userlog;
use app\models\userlogSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserlogController implements the CRUD actions for Userlog model.
 */
class UserlogController extends BmcController {

  /**
   * {@inheritdoc}
   */
  public function behaviors() {
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
   * Lists all Userlog models.
   * @return mixed
   */
  public function actionIndex() {
    $searchModel = new userlogSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    return $this->render('index', ['searchModel' => $searchModel,'dataProvider' => $dataProvider]);
  }

	public function actionPrint(){
		$param = Yii::$app->request->queryParams; 
		$param['print']=1;  
		$searchModel = new userlogSearch();
    $dataProvider = $searchModel->search($param);

		$html = $this->renderFile(Yii::$app->viewPath.'/userlog/print.php', ['dataProvider'=>$dataProvider]); //echo $html;die();
    $setting = array('title' =>'Audit Trail');
    $this->printXls($html, false, $setting);
	}
	
  /**
   * Displays a single Userlog model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionView($id) {
    return $this->render('view', [
          'model' => $this->findModel($id),
    ]);
  }

  /**
   * Finds the Userlog model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Userlog the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id) {
    if (($model = Userlog::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
  }

}
