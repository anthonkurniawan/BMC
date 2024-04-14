<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tagname */

$this->title = Yii::t('app', 'Update Tagname: ' . $model->name, [
  'nameAttribute' => '' . $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tagnames'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="tagname-update">

	<b class="left clear" style="font-size:20px"><?= Html::encode($this->title) ?></b>

  <?=
  $this->render('_form', [
    'model' => $model,
  ])
  ?>

</div>
