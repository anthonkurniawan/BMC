<?php

namespace app\models;

use Yii;
use app\models\Area;
use app\models\TagHeader;

/**
 * This is the model class for table "tagname".
 *
 * @property int $id
 * @property int $areaId
 * @property int $headerId
 * @property string $name
 * @property string $alias
 * @property string $label
 * @property string $desc
 * @property string $spec
 * @property string $spec2
 */
class Tagname extends \yii\db\ActiveRecord {

  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'tagname';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['order_col', 'areaId', 'headerId'], 'integer'],
			[['order_col'], function ($attribute, $param) { 
				$count = Yii::$app->db->createCommand("select count(*) from tagname where areaId=$this->areaId")->queryScalar();
				if($this->order_col > $count) {
					$this->addError($attribute, "The order should not greater than $count");
        }
			}, 'on'=>['update']],
			[['areaId', 'headerId'], 'default', 'value'=>null],
      [['name', 'alias', 'label'], 'required'],
			[['name'], 'unique'],
      [['name'], 'string'],
			[['name', 'label', 'desc', 'spec', 'spec2', 'specLabel'], 'trim'],
			[['label', 'desc', 'spec', 'spec2', 'specLabel'], 'default', 'value' => function ($model, $attribute) {
				return (trim($model->$attribute)==false) ? NULL : $model->$attribute;
			}],
			//[['spec', 'spec2'], 'in', 'range' => ['>', '<', '>=', '<=', '==', '<>', '!=']],
			//[['spec', 'spec2'], 'match', 'pattern' => '/^(\<|\<=|\>|\>=|\==|\!=|\<>)(\s\d+|\d+)$/']
			[['spec', 'spec2'], 'match', 'pattern' => '/^(\<|\<=|\>|\>=|\==|\!=|\<>)(\s\d*|\d*)\.?\d+$/']
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
			'order_col' => "View Order",
      'areaId' => 'Area',
      'headerId' => 'Header Group',
      'name' => 'Name',
      'alias' => 'Alias',
      'label' => 'Label',
      'desc' => 'Description',
      'spec' => 'Spec',
      'spec2' => 'Spec2',
			'specLabel'=>"Label Spec",
    ];
  }

  /**
   * {@inheritdoc}
   * @return agnameQuery the active query used by this AR class.
   */
  public static function find() {
    return new TagnameQuery(get_called_class());
  }

  public function getArea() {
    return $this->hasOne(Area::className(), ['id' => 'areaId']);
  }
	
	public function getTagHeader() {
    return $this->hasOne(TagHeader::className(), ['id' => 'headerId']);
  }

	public function beforeSave($insert){
		if (!parent::beforeSave($insert)) {
			return false;
		}
		
		$this->spec = $this->spec ? htmlentities($this->spec) : null;
		$this->spec2 = $this->spec2 ? htmlentities($this->spec2) : null;
		return true;
	}
	
	public function getListDept(){
		$dept = Dept::findBySql('Select id, label from dept')->asArray()->all();
		$list=[null => '-'];
		foreach($dept as $v){
			$list[$v['id']] = $v['label'];
		}
		return $list;
	}
	
	public function getListArea(){
		$area = Area::findBySql('Select id, label from area')->asArray()->all();
		$list=[null => '-'];
		foreach($area as $v){
			$list[$v['id']] = $v['label'];
		}
		return $list;
	}
	
	public function getListGroupHeader(){
		$header = Area::findBySql('select id,name from tagheader')->asArray()->all();
		$list=[null => '-'];
		foreach($header as $v){
			$list[$v['id']] = $v['name'];
		}
		return $list;
	}

}
