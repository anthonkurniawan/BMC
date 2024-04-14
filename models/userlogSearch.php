<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Userlog;

/**
 * userlogSearch represents the model behind the search form of `app\models\Userlog`.
 */
class userlogSearch extends Userlog {

  public $username;

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['id', 'userid'], 'integer'],
      [['date', 'event', 'username'], 'safe'],
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
  public function search($params) { //echo "PARAM"; print_r($params); die();
    $query = Userlog::find()->joinWith('user')->orderBy(['date'=>SORT_DESC]);
    
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
       'date',
       'userid',
       'user.username' => [
         'asc' => ['user.username' => SORT_ASC],
         'desc' => ['user.username' => SORT_DESC],
        ],
        'event'
       ]
    ]);

    $this->load($params);

    if (!$this->validate()) {
      return $dataProvider;
    }

    // grid filtering conditions
    $query->andFilterWhere([
      'id' => $this->id,
    ]);

    $query->andFilterWhere(['like', 'user.username', $this->username]);
    $query->andFilterWhere(['like', 'event', $this->event]);
		if($this->date){
			$day = new \DateTime($this->date);
			$day = $day->modify('+1 day')->format('Y-m-d');
			$query->andWhere(['>', 'date', $this->date]);
			$query->andWhere(['<', 'date', $day]);
		}
    return $dataProvider;
  }

}
