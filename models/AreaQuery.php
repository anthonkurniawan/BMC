<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[area]].
 *
 * @see area
 */
class AreaQuery extends \yii\db\ActiveQuery {
  /**
   * {@inheritdoc}
   * @return area[]|array
   */
  public function all($db = null) {
    return parent::all($db);
  }

  /**
   * {@inheritdoc}
   * @return area|array|null
   */
  public function one($db = null) {
    return parent::one($db);
  }

}
