<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\tagnameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Tag Management');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
getPrint = function(el){ console.log('Print..', el);
	event.preventDefault();
	var href = el[0].href + window.location.search;
	window.location = href; //$('#print').attr('href') console.log(window.location);
}
"
//,$this::POS_READY
);
?>

<style>
	table tr th, table tr td{padding:5px!important}
	.pagination{margin:0}
	.table{margin-bottom:10px;}
</style>
	
<div class="tagname-index clear">
  <h3><?= Html::encode($this->title) ?></h3>
  <?php 
	Pjax::begin(); 
	$user = Yii::$app->user->getIdentity();
	?>

  <?=
  GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
		'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
		'tableOptions' => ['class' => 'table table-striped table-bordered list'],
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
      //'id','areaId','alias','headerId',
			 [
        'attribute' => 'order_col',
				'label'=>'Order',
				'contentOptions'=>['style'=>'text-align:center'],
      ],
      [
        'attribute' => 'areaName',
        'value' => 'area.name',
				'label'=>'Area',
				'options'=>['width'=>110]
      ],
      'name',
      'label',
      'desc',
			[
        'attribute' => 'spec',
				//'label'=>'Area',
				'format'=>'html',
				'options'=>['width'=>80],
      ],
			[
        'attribute' => 'spec2',
				'format'=>'html',
				'options'=>['width'=>80]
      ],
			[
        'attribute' => 'specLabel',
				'format'=>'html',
				'options'=>['width'=>80]
      ],
			[
        'attribute' => 'headerGroup',
        'value' => 'tagHeader.name',
				'label'=>'Group',
      ],
      //['class' => 'yii\grid\ActionColumn', 'template'=>'{update}'],
			[
        'class' => 'yii\grid\ActionColumn', 
        'template' => $user && $user->username=='adminkeren' ? '{view} {update} {delete}' : '{update}',
        //'buttonOptions' => ['onclick'=>'loadGridAction($(this));'],
        'buttons' =>[
					// 'delete' => function ($url, $model, $key) {
						// return Html::a("<span class='glyphicon glyphicon-trash text-primary'></span>", $url, ['title' =>'Delete','data-pjax'=>'0','onclick'=>'']);
          // }
        ],
				//'options'=>['width'=>65]
      ],
    ],
		'pager' => [//'class'=>'yii\widgets\LinkPager'
      'options' => ['class' => 'pagination left'],
      'linkOptions' => [],
      'firstPageLabel' => 'First',
      'lastPageLabel' => 'Last',
      'registerLinkTags' => false, // default: false
      'hideOnSinglePage' => true, // default: true
      'maxButtonCount' => 3,
      'prevPageCssClass' => 'prev',
      'nextPageCssClass' => 'next',
      'activePageCssClass' => 'active',
      'disabledPageCssClass' => 'disabled',
      'nextPageLabel' => '&raquo,',
      'prevPageLabel' => '&laquo,',
    ],
		'summary' => '<div style="margin-top:10px;margin-bottom:5px;height:21px" class="left">
			<div class="left">
				Showing <b>{begin, number}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, one {item} other {Tags}} 
			</div>
			<span class="loader left" style="margin:2px 0px 0px 5px"></span></div>
			<p class="right">'.Html::a("Create new tag", ["create"], ["data-pjax"=>"0","class"=>"btn btn-outline-primary"]).'</p>',
  ]);
  ?>
	
	<div class="right" style="">
		<?php
		echo Html::a('Excel', ['/tagname/print'],['data-pjax'=>"0",'id'=>'print','class'=>'btn btn-outline-primary','style'=>'margin-left:7px','onclick'=>'js:getPrint($(this));']);
		?>
	</div>
	
<?php Pjax::end(); ?>
</div>

<?php 
$this->registerJs("
$(document).ready(function(){
	$('div.container').width(document.body.scrollWidth-100);
});
"
//,$this::POS_READY
);
?>
