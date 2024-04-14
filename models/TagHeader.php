<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tagHeader".
 *
 * @property int $id
 * @property string $name
 * @property string $specLabelGroup
 */
class TagHeader extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tagheader';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id'], 'integer'],
            [['name', 'specLabelGroup'], 'string'],
            [['id'], 'unique'],
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
            'specLabelGroup' => 'Spec Label Group',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TagHeaderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TagHeaderQuery(get_called_class());
    }
}
