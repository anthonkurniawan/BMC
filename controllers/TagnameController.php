<?php

namespace app\controllers;

use Yii;
use app\models\Tagname;
use app\models\tagnameSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Exception;
use app\models\Area;

/**
 * TagnameController implements the CRUD actions for Tagname model.
 */
class TagnameController extends BmcController {

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
   * Lists all Tagname models.
   * @return mixed
   */
  public function actionIndex() {
    $searchModel = new tagnameSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$this->log("View Tagname List");
    return $this->render('index', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Displays a single Tagname model.
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
   * Creates a new Tagname model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate() {
    $model = new Tagname(); 
		
		if($model->load(Yii::$app->request->post())){
			$db = Yii::$app->db;
			$model->alias =  preg_replace('/\W+/', '_', $model->name); 
			if($model->areaId) $model->order_col = $db->createCommand("select count(*)+1 from tagname where areaId=$model->areaId")->queryScalar(); //--to make order_col at tagname

			$transaction = $db->beginTransaction();
			try{
				if($model->save()) {
					$this->alterTableTags('add', $model->alias);
					if($model->areaId && $model->headerId) Area::updateHeaderCount($model->areaId); 
					$msg = "Create new tagname '$model->name'";
					Yii::$app->session->setFlash('success', $msg);
					$this->log($msg);
					$transaction->commit();
					return $this->redirect(['index']);
				} 
			}catch(Exception $e){
				$err = preg_replace('/\n/', '<br>', $e->getMessage()); 
				Yii::$app->session->setFlash('warning', $err);
				$transaction->rollBack(); 
			}
		}
		
    return $this->render('create', ['model' => $model]);
  }
	
  /**
   * Updates an existing Tagname model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionUpdate($id) {
    $model = $this->findModel($id);
		$model->setScenario('update');
		$post = Yii::$app->request->post(); 
		
		if($model->load($post)){
			$db = Yii::$app->db;
			$changeAttr = $this->getChangesAttr($model);
			$model->alias =  preg_replace('/\W+/', '_', $model->name); 
			$oldAlias = $model->getOldAttribute('alias');
			$oldOrder = $model->getOldAttribute('order_col');
			$oldHeaderId = $model->getOldAttribute('headerId');
			
			$transaction = $db->beginTransaction();
			try{
				if($oldOrder && $model->order_col != $oldOrder){
					$id = $db->createCommand("select id from tagname where areaId=$model->areaId and order_col = $model->order_col")->queryScalar();
					if($id) $db->createCommand("Update tagname set order_col=$oldOrder Where id=$id")->execute();
				}
				if($model->save()) {
					if($model->alias != $oldAlias){
            $this->alterTableTags('change', $model->alias, $oldAlias);
					}
					if($model->headerId != $oldHeaderId){
						Area::updateHeaderCount($model->areaId);
					}
					
					$msg = "Update tagname '$model->name' - $changeAttr";
					Yii::$app->session->setFlash('success', $msg);
					$this->log($msg);
					$transaction->commit();
					return $this->redirect(['index']);
				}
			}catch(Exception $e){
				$err = preg_replace('/\n/', '<br>', $e->getMessage());
				Yii::$app->session->setFlash('warning', $err);
				$transaction->rollBack();
				return $this->render('update', ['model' => $model,]);
			}
		}
		
		$model->spec = html_entity_decode($model->spec);  // opposite htmlentities
		$model->spec2 = html_entity_decode($model->spec2);
    return $this->render('update', ['model' => $model,]);
  }
	
	public function actionPrint(){
		$param = Yii::$app->request->queryParams; 
		$param['print']=1;  
		$searchModel = new tagnameSearch();
    $dataProvider = $searchModel->search($param);
		
		$html = $this->renderFile(Yii::$app->viewPath.'/tagname/print.php', ['dataProvider'=>$dataProvider]); 
		$setting = array('title' =>'Tag Management');
    $this->printXls($html, false, $setting);
	}
  /**
   * Deletes an existing Tagname model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionDelete($id) {
    $model = $this->findModel($id); 
		$db = Yii::$app->db;
		$transaction = $db->beginTransaction();
			try{
				if($model->delete()) {
					$this->alterTableTags('drop', $model->alias);
					Area::updateHeaderCount($model->areaId);
					$msg = "Delete tagname '$model->name'";
					Yii::$app->session->setFlash('success', $msg);
					$this->log($msg);
					$transaction->commit();
				}
			}catch(Exception $e){
				$err = preg_replace('/\n/', '<br>', $e->getMessage()); 
				Yii::$app->session->setFlash('warning', $err);
				$transaction->rollBack();
			}
			return $this->redirect(['index']);
  }

	public function alterTableTags($action, $tagname, $tagname_old=null){
    if(!$action || !$tagname) throw new \yii\db\Exception('Parameter action & tagname is needed!');
		$db_eng = Yii::$app->params['db_engine'];
		if($db_eng=='mssql')
			$this->alterMssql($action, $tagname, $tagname_old);
		else
			$this->alterMysql($action, $tagname, $tagname_old);
  }
	
	protected function alterMssql($action, $tagname, $tagname_old){
		$col = 'name'; 
		$db = Yii::$app->db;
		$tagsdataTbl = $db->createCommand("SELECT $col FROM sys.tables WHERE $col LIKE '%tagsdata%'")->queryAll();
		if(!empty($tagsdataTbl)){
      foreach($tagsdataTbl as $k=>$v){
        if($action=='change'){
          $db->createCommand("ALTER TABLE $v[$col] CHANGE $tagname_old $tagname REAL NULL")->execute(); #MYSQL
        }else if($action=='add'){
          $db->createCommand("ALTER TABLE $v[$col] ADD $tagname REAL NULL")->execute(); #MYSQL
        }else if($action=='drop'){
          $db->createCommand("ALTER TABLE $v[$col] DROP COLUMN $tagname")->execute(); #MYSQL
        }
      }
    }
	}
	
	# MYSQL
	protected function alterMysql($action, $tagname, $tagname_old){
		$col = 'tables_in_bmc';
		$db = Yii::$app->db;
    $tagsdataTbl = $db->createCommand("SHOW TABLES WHERE $col like '%tagsdata%'")->queryAll();
		if(!empty($tagsdataTbl)){
      foreach($tagsdataTbl as $k=>$v){
        if($action=='change'){
          $db->createCommand("ALTER TABLE $v[$col] CHANGE $tagname_old $tagname DOUBLE(10,2) NULL DEFAULT NULL")->execute(); #MYSQL
        }else if($action=='add'){
          $db->createCommand("ALTER TABLE $v[$col] ADD $tagname DOUBLE(10,2) NULL DEFAULT NULL")->execute(); #MYSQL
        }else if($action=='drop'){
          $db->createCommand("ALTER TABLE $v[$col] DROP COLUMN $tagname")->execute(); #MYSQL
        }
      }
    }
	}
	
  /**
   * Finds the Tagname model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Tagname the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id) {
    if (($model = Tagname::findOne($id)) !== null) {
      return $model;
    }
    throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
  }

}
