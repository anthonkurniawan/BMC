<?php

namespace app\models;

use Yii;
use dektrium\user\models\User;

/**
 * This is the model class for table "userlog".
 *
 * @property int $id
 * @property string $date
 * @property int $userid
 * @property string $event
 */
class Userlog extends \yii\db\ActiveRecord {

  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'userlog';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['date', 'userid', 'event'], 'required'],
      [['date'], 'safe'],
      [['userid'], 'integer'],
      [['event'], 'string'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'date' => 'Date',
      'userid' => 'Userid',
      'event' => 'Event',
    ];
  }

  public function getUser() {
    return $this->hasOne(User::className(), ['id' => 'userid']);
  }

  /**
   * {@inheritdoc}
   * @return UserlogQuery the active query used by this AR class.
   */
  public static function find() {
    return new UserlogQuery(get_called_class());
  }

}
