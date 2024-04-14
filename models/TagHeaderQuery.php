<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TagHeader]].
 *
 * @see TagHeader
 */
class TagHeaderQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return TagHeader[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TagHeader|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
