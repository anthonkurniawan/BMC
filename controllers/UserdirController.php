<?php

namespace app\controllers;

use Yii;
use app\models\Userdir;
use app\models\UserdirSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserdirController implements the CRUD actions for Userdir model.
 */
class UserdirController extends BmcController {

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

  public function actionTest(){
    $html = $this->renderFile('/media/DATA/app/PHP/YII2/ro/runtime/test.html');
    $setting = array('title' =>'xxx');
    $this->printXls($html, false, $setting);
  }

  /**
   * Lists all Userdir models.
   * @return mixed
   */
  public function actionIndex() {
		\yii\helpers\Url::remember('', 'actions-redirect');
    $searchModel = new UserdirSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$this->log("View User List");

    if (\Yii::$app->request->isAjax)
      return $this->renderAjax('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
      ]);
    else
      return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
      ]);
  }

	public function actionPrint(){
		$param = Yii::$app->request->queryParams; 
		$param['print']=1;
		$searchModel = new UserdirSearch();
    $dataProvider = $searchModel->search($param);

		$html = $this->renderFile(Yii::$app->viewPath.'/userdir/print.php', ['dataProvider'=>$dataProvider]);
		$setting = array('title' =>'User Management');
    $this->printXls($html, false, $setting);
	}
	
  /**
   * Displays a single Userdir model.
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
   * Creates a new Userdir model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate() {    
    $model = new Userdir();
		$model->setScenario('create');
		$this->performAjaxValidation($model);
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$msg = "Create new user for $model->username";
			Yii::$app->session->setFlash('success', $msg);
			$this->log($msg);
			return $this->redirect('index');
    }
    return $this->render('create', [
      'model' => $model,
    ]);
  }

  /**
   * Updates an existing Userdir model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
	public function actionUpdate($id) {
    $model = $this->findModel($id); 
		$model->setScenario('update');
		$this->performAjaxValidation($model);
		$request = Yii::$app->request;
		$post = $request->post();
		
    if($model->load($post)) { 
			$changeAttr = $this->getChangesAttr($model);
			$transaction = Yii::$app->db->beginTransaction(); 
			$user = $model->user;
			$userSaved = true; 
			if($user){
				$user->setAttributes(['username'=>$model->username, 'email'=>$model->email, 'updated_at'=>time()]);
				$userSaved = $user->save();
			}
			$modelSaved = $userSaved && $model->save();
			
      if($modelSaved){
				$msg = "Update User id:$id. ".$changeAttr;
				$transaction->commit();
				Yii::$app->session->setFlash('success', $msg);
        $this->log($msg);
				return $this->redirect(['index']);
      }
      else{
				Yii::$app->session->setFlash('msg', $model->error);
        $transaction->rollBack();
			}
    }
    return $this->render('update', ['model' => $model]);
  }

  /**
   * Deletes an existing Userdir model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionDelete($id) {
		$model = $this->findModel($id);
		if($model){
			$msg = "Delete user account $model->username"; 
			$model->delete();  // IF 1 THEN FIND USER MODEL
			Yii::$app->session->setFlash('success', $msg);
			$this->log($msg);
		}
    return $this->redirect(['index']);
  }

  /**
   * Finds the Userdir model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Userdir the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id) {
    if (($model = Userdir::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
  }

}
