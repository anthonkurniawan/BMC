<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tagname;

/**
 * tagnameSearch represents the model behind the search form of `app\models\Tagname`.
 */
class tagnameSearch extends Tagname {

  public $areaName;
	public $headerGroup;

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['id','areaId', 'headerId'], 'integer'],
      [['name', 'alias', 'label', 'desc','areaName'], 'safe'],
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
    $query = Tagname::find()->joinWith('area')->joinWith('tagHeader');
		$query->orderBy([
			'areaId' => SORT_ASC,
			'order_col' => SORT_ASC,
		]);
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
        'order_col','name', 'alias', 'label', 'desc',
        'areaName' => [
					'asc' => ['area.name' => SORT_ASC],
          'desc' => ['area.name' => SORT_DESC],
					//'label' => 'Full Name',
					//'default' => SORT_ASC
        ],
      ]
    ]);
		//}

    $this->load($params);

    if (!$this->validate()) {
      // uncomment the following line if you do not want to return any records when validation fails
      // $query->where('0=1');
      return $dataProvider;
    }

    // grid filtering conditions
    $query->andFilterWhere([
      'id' => $this->id,
      'area.name' => $this->areaName,
        //'headerId' => $this->headerId,
    ]);

    $query->andFilterWhere(['like', 'tagname.name', $this->name])
        ->andFilterWhere(['like', 'alias', $this->alias])
        ->andFilterWhere(['like', 'tagname.label', $this->label])
        ->andFilterWhere(['like', 'desc', $this->desc])
				->andFilterWhere(['like', 'specLabel', $this->specLabel])
        ->andFilterWhere(['like', 'area.name', $this->areaName]);
    return $dataProvider;
  }

}
