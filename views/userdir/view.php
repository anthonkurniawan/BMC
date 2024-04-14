<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Userdir */

$this->title = "User Detail ID:$model->idx";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Userdirs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
table tr th {text-align:left;width:200px}
</style>

<div class="userdir-view clear">

  <b class="left" style="font-size:20px"><?= Html::encode($this->title) x?></b>

  <p class="right">
    <?= Html::a('Update', ['update', 'id' => $model->idx], ['class' => 'btn btn-primary']) ?>
    <?=
    Html::a('Delete', ['delete', 'id' => $model->idx], [
      'class' => 'btn btn-danger',
      'data' => [
        'confirm' =>'Are you sure you want to delete this user?',
        'method' => 'post',
      ],
    ])
    ?>
  </p>

  <?=
  DetailView::widget([
    'model' => $model,
    'attributes' => [
      //'idx',
			'username',
			'email:email',
			[
				'label'=>"Departement",
        'attribute' => 'dept',
				'value'=> function($model, $i){
					if($model->depts) return $model->depts->label;
				},
      ],
			[
        'attribute' => 'role',
        'value' => function($model, $widget){
					return $model->getRoleLabel($model->role);
				},
      ],
			[
				'label'=>'Registered',
        'attribute' => 'created_at',
        'value' => $model->user->created_at,
				'format'=>'date'
      ],
			[
				'label'=>'Confirmed',
        'attribute' => 'confirmed_at',
        'value' => $model->user->confirmed_at,
				'format'=>'date'
      ],
			[
				'label'=>'Blocked',
        'attribute' => 'blocked_at',
        'value' => $model->user->blocked_at,
				'format'=>'date'
      ],
			[
				'label'=>'Last Updated',
        'attribute' => 'updated_at',
        'value' => $model->user->updated_at,
				'format'=>'date'
      ],
			[
				'label'=>'Last Login',
        'attribute' => 'last_login_at',
				'value' => function ($model) {
          if (!$model->user || ($model->user && (!$model->user->last_login_at || $model->user->last_login_at == 0))) {
						return null;
					}else {
						return date('d-m-Y H:m', $model->user->last_login_at);
          }
				}
      ],
			[
				'label'=>'Last Update Password.',
        'attribute' => 'updatePwdAt',
        'value' => $model->user->updatePwdAt,
				'format'=>'date'
      ],
			[
        'label' => 'Confirmation',
				'attribute' => null,
				'value' => function ($model) {
          if ($model->user){
						if($model->user->isConfirmed) {
              return '<div class="text-center"><span class="text-success">' . Yii::t('user', 'Confirmed') . '</span></div>';
						}elseif(!$model->user->isBlocked){
              return Html::a(Yii::t('user', 'Confirm'), ['user/admin/confirm', 'id' => $model->user->id], [
               'class' => 'btn btn-xs btn-success btn-block',
               'data-method' => 'post',
               'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
              ]);
						}
					}
        },
        'format' => 'raw',
      ],
			[
        'label' => 'Block Status',
				'attribute' => null,
				'value' => function ($model) {
          if ($model->user){
						if($model->user->isBlocked) {
              return Html::a('Unblock', ['user/admin/block', 'id' => $model->user->id], [
                'class' => 'btn btn-xs btn-success btn-block',
								'data-method' => 'post',
								'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
             ]);
						} else {
							return Html::a(Yii::t('user', 'Block'), ['user/admin/block', 'id' => $model->user->id], [
								'class' => 'btn btn-xs btn-danger btn-block',
								'data-method' => 'post',
								'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
							]);
						}
          }
        },
        'format' => 'raw',
      ],
    ],
  ])
  ?>

</div>
