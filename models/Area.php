<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Area".
 *
 * @property int $id
 * @property int $deptId
 * @property string $name
 * @property string $label
 * @property int $headerCount
 */
class Area extends \yii\db\ActiveRecord {

  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'area';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['deptId', 'name', 'label'], 'required'],
      [['deptId', 'headerCount'], 'integer'],
      [['name'], 'string', 'max' => 20],
      [['label'], 'string', 'max' => 100],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'deptId' => 'Dept ID',
      'name' => 'Code',
      'label' => 'Label',
      'headerCount' => 'Header Count',
    ];
  }

  /**
   * {@inheritdoc}
   * @return areaQuery the active query used by this AR class.
   */
  public static function find() {
    return new AreaQuery(get_called_class());
  }

  public function findByName($name) {
    return $this->findOne(['name' => $name]);
  }

	public function getDept() {
    return $this->hasOne(Dept::className(), ['id' => 'deptId']);
  }
	
	public static function updateHeaderCount($areaId){
    if(!$areaId) return;
		$db = Yii::$app->db;
		$count = $db->createCommand("select count(distinct headerId) from tagname where areaId=$areaId")->queryScalar(); //--to make HeaderCount at Area
		return $db->createCommand("update area set headerCount=$count where id=$areaId")->execute();
	}
}
