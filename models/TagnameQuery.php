<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Tagname]].
 *
 * @see Tagname
 */
class TagnameQuery extends \yii\db\ActiveQuery {
  /**
   * {@inheritdoc}
   * @return Tagname[]|array
   */
  public function all($db = null) {
    return parent::all($db);
  }

  /**
   * {@inheritdoc}
   * @return Tagname|array|null
   */
  public function one($db = null) {
    return parent::one($db);
  }

}
