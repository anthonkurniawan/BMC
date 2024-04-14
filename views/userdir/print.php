<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->registerCssFile(Yii::$app->homeUrl.'css/site.css');
$this->title = Yii::t('app', 'User Management');
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
		<style type="text/css">
		.left{ float:left }
		.right{ float:right }
		.clear{ clear:both }
		.report{ border-width:1px 0px 0px 1px; }
		table.print{ width:100%;border-spacing:0px;text-align:left;background-color:white;border-top:1px solid;border-left:1px solid;border-bottom:none}
		table.print tr th, table.print tr td{ border:1px black;border-style:solid;border-left:white;border-top:white }
		table.print tr th{font-size:12px}
		table.print tr td{font-size:12px}
		table.print tr th{background:#C3DEE4;text-align:center } 
		table.print tr th, table.print tr td{padding:1px 1px;}
		table.print tr td.txtErr{ text-align:center;color:red }
		</style>
    <?php $this->head() ?>
  </head>
	
  <body>
    <?php $this->beginBody() ?>	
		<h3><?=$this->title?></h3>
		<?=
		GridView::widget([
    'dataProvider' => $dataProvider,
		'tableOptions' => ['class' => 'report print'],
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
			[
        'attribute' => 'username', // 'lastname'
        'label' => 'Username',
				'enableSorting' => false
      ],
			[
        'attribute' => 'email',
				'enableSorting' => false
      ],
      [
        'attribute' => 'dept',
        'value' => 'depts.label',
				'enableSorting' => false
      ],
			[
        'attribute' => 'role',
        'value' => function($model, $i){
					return $model->getRoleLabel($model->role);
				},
				'enableSorting' => false
      ],
			[
				'label'=>'Registered',
        'attribute' => 'created_at',
        'value' => 'user.created_at',
				'format'=>'date',
				'enableSorting' => false
				//'contentOptions'=>['style'=>'text-align:center'],
      ],
			[
				'label'=>'Confirmed',
        'attribute' => 'confirmed_at',
        'value' => 'user.confirmed_at',
				'format'=>'date',
				'enableSorting' => false
      ],
			[
				'label'=>'Blocked',
        'attribute' => 'blocked_at',
        'value' => 'user.blocked_at',
				'format'=>'date',
				'enableSorting' => false
      ],
			[
				'label'=>'Updated',
        'attribute' => 'updated_at',
        'value' => 'user.updated_at',
				'format'=>'date',
				'enableSorting' => false
      ],
			[
				'label'=>'Last Login',
        'attribute' => 'last_login_at',
				'format'=>'date',
				'value' => function ($model) {
          if (!$model->user || ($model->user && (!$model->user->last_login_at || $model->user->last_login_at == 0))) {
						return null;
					}else {
						return $model->user->last_login_at;
          }
				},
				'enableSorting' => false
      ],
			[
				'header' => Yii::t('user', 'Confirmation'),
        'value' => function ($model) {
					if ($model->user){
						return $model->user->isConfirmed ? 'Confirmed' : "Unconfirmed";
					}
        },
        'format' => 'raw',
				'contentOptions'=>['style'=>'text-align:center'],
			],
      [
				'header' => Yii::t('user', 'Block Status'),
        'value' => function ($model) {
					if ($model->user){
						return $model->user->isBlocked ? "Blocked" : "-";
          }
				},
				'contentOptions'=>['style'=>'text-align:center'],
      ],
    ],
  ]);
  ?>
		
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>