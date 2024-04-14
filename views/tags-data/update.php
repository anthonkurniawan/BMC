<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TagsData */

$this->title = Yii::t('app', 'Update Tags Data: ' . $model->tanggal, [
    'nameAttribute' => '' . $model->tanggal,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tags Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tanggal, 'url' => ['view', 'id' => $model->tanggal]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tags-data-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
