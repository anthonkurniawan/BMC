<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Userdir;

/**
 * UserdirSearch represents the model behind the search form of `app\models\Userdir`.
 */
class UserdirSearch extends Userdir {

	public $username;
	//public $name;
	public $created_at;
	public $updated_at;
	public $last_login_at;
	public $confirmed_at;
	public $blocked_at;
	//public $flags;
	
  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['email', 'role'], 'safe'],
			[['username','dept'], 'string'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function scenarios() {
    // bypass scenarios() implementation in the parent class
    return Model::scenarios();
  }


  /**
   * Creates data provider instance with search query applied
   *
   * @param array $params
   *
   * @return ActiveDataProvider
   */
	 public function search($params) {
    $query = Userdir::find()->joinWith('user')->joinWith('profile')->joinWith('depts');

    // add conditions that should always apply here
    $dataProvider = new ActiveDataProvider([
      'query' => $query,
    ]);
		
		if(isset($params['print'])) $dataProvider->setPagination(false);
		else{
			$dataProvider->setPagination([
        'defaultPageSize' => 10,
        'pageSize' => 10, 
      ]);
		}
			
		$dataProvider->setSort([
      'attributes' => [
				'email', 'dept', 'role',
        'username' => [
          'asc' => ['user.username' => SORT_ASC],
          'desc' => ['user.username' => SORT_DESC],
        ],
				'created_at',
				'updated_at',
				'last_login_at',
				'confirmed_at',
				'blocked_at',
				//'flags',
      ]
    ]);

    $this->load($params);
		
    if (!$this->validate()) {
      return $dataProvider;
    }
    // grid filtering conditions
    $query->andFilterWhere(['like', 'userdir.email', $this->email])
			->andFilterWhere(['like', 'userdir.username', $this->username])
      ->andFilterWhere(['like', 'role', $this->role])
			->andFilterWhere(['like', 'dept.label', $this->dept]);
			// ->andFilterWhere(['like', 'created_at', $this->created_at])
			// ->andFilterWhere(['like', 'updated_at', $this->updated_at])
			// ->andFilterWhere(['like', 'last_login_at', $this->last_login_at])
			// ->andFilterWhere(['like', 'confirmed_at', $this->confirmed_at])
			// ->andFilterWhere(['like', 'blocked_at', $this->blocked_at]);
    return $dataProvider;
  }

}
