<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Userlog;

class LogController extends Controller
{
	public function log($event){
		if(!Yii::$app->user->id) return;
		$model = new Userlog();
		$model->setAttributes(['date'=>date('Y-m-d H:i'), 'userid'=>Yii::$app->user->id, 'event'=>$event]);
		$model->insert(false);
	}
	
	public function getChangesAttr($model){
		$changesAtt = $model->getDirtyAttributes(); 
		$count = count($changesAtt);
		if($count){
			$changeLog = '';
			$i=0;
			foreach($changesAtt as $k=>$v){  
				$old = ($k=='spec' || $k=='spec2') ? html_entity_decode($model->getOldAttribute($k)) : $model->getOldAttribute($k);		//echo "<br>=>i:$i, $k "; //var_dump($v); var_dump($old); var_dump($old == $v);
				if($old != $v){
					if($k==='role') $changeLog.="$k:'".$model->getRoleLabel($old)."' to '".$model->getRoleLabel($v) . ($i < $count ? "'," : "'");
					else $changeLog.="$k:'$old' to '$v'" . ($i < $count ? ', ' : '');
					$i++;
				}
			} 
			$changeLog = $i ? "Change ".$changeLog : 'Nothing to changes';
			return $changeLog;
		}
		else return 'Nothing to changes';
	}
	
	protected function performAjaxValidation($model){
    if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			$res = Yii::$app->response; 
      $res->format = \yii\web\Response::FORMAT_JSON;
			$res->data   = \yii\widgets\ActiveForm::validate($model);
			$res->send();
			Yii::$app->end();
    }
	}
	
	public function beforeAction($action) {
		if($action->id==='login'){
			return true;
		}

    if (!parent::beforeAction($action)) {
        return false;
    }

		try{
			if (Yii::$app->user->isGuest){
				Yii::$app->session->remove('identity');
				return Yii::$app->user->loginRequired();
			}
			$user = Yii::$app->user->getIdentity(); 
			if($user){		
				if($user->isBlocked){
					Yii::$app->session->destroy();
					Yii::$app->user->switchIdentity(null);

					return $this->goHome();
				}
				
				if($action->id=='account') 
					return true;
				
				$lastPwdUpdate = date('Y-m-d', $user->updatePwdAt);
				$expireDate = date('Y-m-d', strtotime('-3 month')); 
				if ($lastPwdUpdate <= $expireDate) { 
					\yii\helpers\Url::remember('', 'actions-redirect'); 
					$this->redirect(\yii\helpers\Url::to(['/user/settings/account']));
				}
				
				$ctrl = $action->controller->id;
				if(in_array($ctrl, ['userdir', 'userlog', 'tagname'])){  
					$identity = \Yii::$app->session->get('identity');
					if(!$identity['isAdmin']) {
						$this->redirect(\yii\helpers\Url::to(['/error/restricted'])); 
						return false;
					}
					else return true;
				}
				
				return true;
			}
			else{  echo "No indentity";die();
				Yii::$app->session->destroy();
			}
		}catch(yii\db\Exception $e){
			\yii\helpers\Url::remember('', 'actions-redirect');
			$this->redirect(['error/db']);
			return false;
		}
		return true;
  }


	public static function setSession($user=null){ 
		$session = Yii::$app->session;
		$user = $user->identity;
		if($user){
			$dept = $user->userdir->dept; 
			$isAdmin = $user->isAdmin;
			$areaAcc = $isAdmin ? 'all' : $dept;
			
			$identity = [
				'id'=>$user->id,
				'username'=>$user->username,
				'dept'=>$dept,
				'areaAccess'=>self::getAreaByDept($areaAcc),
				'role'=>$user->userdir->role,
				'email'=>$user->userdir->email,
				'isAdmin'=>$isAdmin,
				'isGuest'=>false,
				'activeMenuDept_id'=>$user->userdir->dept,
				'activeMenuDept_name'=>$user->userdir->depts->label,
			];  
		}else{
			$identity=['isGuest'=>true, 'isAdmin'=>false, 'dept'=>null];
		}
		$session->set('identity', $identity); 
		return true;
	}
	
	public static function getAreaByDept($deptId){
		if(!$deptId) return;
		$sql = $deptId=='all' ? "select name from area" : "select name from area where deptId=$deptId";
		$areas = Yii::$app->db->createCommand($sql)->queryAll();
		foreach($areas as $d){
			$area[] = strtolower($d['name']);
		}
		return $area;
	}
	
}
?>
