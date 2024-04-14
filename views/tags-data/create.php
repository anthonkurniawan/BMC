<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TagsData */

$this->title = Yii::t('app', 'Create Tags Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tags Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tags-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
