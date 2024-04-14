<?php

namespace app\models;

use Yii;
use dektrium\user\models\User;
use dektrium\user\models\Profile;

/**
 * This is the model class for table "userdir".
 *
 * @property int $idx
 * @property string $email
 * @property string $role
 */
class Userdir extends \yii\db\ActiveRecord {

  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'userdir';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
		$rules =  array_merge(
			Yii::$app->params['rules']['username'], Yii::$app->params['rules']['email'], 
			//[[['role', 'dept'], 'integer']],
			[[['dept', 'role'], 'required']]
		);
		//$rules = Yii::$app->params['name_email_rules'];
		return $rules;
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'idx' => 'Idx',
			'username' => "Username",
      'email' => 'Email',
      'role' => 'Role',
			'dept' => 'Dept.'
    ];
  }

	public function getUser() {
    return $this->hasOne(User::className(), ['id_dir' => 'idx']);
  }
	
	public function getProfile() {
    return $this->hasOne(Profile::className(), ['user_id' => 'idx']);
  }
	
	public function getDepts() {
    return $this->hasOne(Dept::className(), ['id' => 'dept']);
  }
	public function getDeptByName() {
    return $this->hasOne(Dept::className(), ['label' => 'dept']);
  }
  /**
   * {@inheritdoc}
   * @return UserdirQuery the active query used by this AR class.
   */
  public static function find() {
    return new UserdirQuery(get_called_class());
  }

	public function getRoleLabel($role){
		switch($role){
			case 'adm': return 'Administrator'; break;
			case 'spv': return 'Supervisor'; break;
			default: return "Operator"; break;
		}
	}
	
	public function getListDept(){
		$dept = Dept::findBySql('Select id, label from dept')->asArray()->all();
		$list=[null => '-'];
		foreach($dept as $v){
			$list[$v['id']] = $v['label'];
		}
		return $list;
	}
}
