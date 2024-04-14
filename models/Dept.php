<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dept".
 *
 * @property int $id
 * @property string $name
 * @property string $label
 */
class Dept extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dept';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'label'], 'required'],
            [['name', 'label'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'label' => 'Label',
        ];
    }

    /**
     * {@inheritdoc}
     * @return DeptQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DeptQuery(get_called_class());
    }
}
