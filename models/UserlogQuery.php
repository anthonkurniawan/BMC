<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Userlog]].
 *
 * @see Userlog
 */
class UserlogQuery extends \yii\db\ActiveQuery {
  /**
   * {@inheritdoc}
   * @return Userlog[]|array
   */
  public function all($db = null) {
    return parent::all($db);
  }

  /**
   * {@inheritdoc}
   * @return Userlog|array|null
   */
  public function one($db = null) {
    return parent::one($db);
  }

}
