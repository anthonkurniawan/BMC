<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Userdir]].
 *
 * @see Userdir
 */
class UserdirQuery extends \yii\db\ActiveQuery {
  /**
   * {@inheritdoc}
   * @return Userdir[]|array
   */
  public function all($db = null) {
    return parent::all($db);
  }

  /**
   * {@inheritdoc}
   * @return Userdir|array|null
   */
  public function one($db = null) {
    return parent::one($db);
  }

}
