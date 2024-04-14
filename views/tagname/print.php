<?php
use yii\helpers\Html;
use yii\grid\GridView;

// use app\assets\AppAsset;
// AppAsset::register($this);
// $this->registerJsFile(Yii::$app->homeUrl.'app.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->homeUrl.'css/site.css');
$this->title = Yii::t('app', 'Tags Management');
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
        'attribute' => 'areaName',
        'value' => 'area.name',
				'label'=>'Area',
				'options'=>['width'=>100],
				'enableSorting' => false
      ],
			[
        'attribute' => 'name',
				'enableSorting' => false
      ],
			[
        'attribute' => 'label',
				'enableSorting' => false
      ],
			[
        'attribute' => 'desc',
				'enableSorting' => false
      ],
			[
        'attribute' => 'spec',
				//'label'=>'Area',
				'format'=>'html',
				'enableSorting' => false,
				'options'=>['width'=>80],
      ],
			[
        'attribute' => 'spec2',
				'format'=>'html',
				'enableSorting' => false,
				'options'=>['width'=>80]
      ],
			[
        'attribute' => 'specLabel',
				'format'=>'html',
				'enableSorting' => false,
				'options'=>['width'=>80]
      ],
    ],
  ]);
  ?>
		
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>