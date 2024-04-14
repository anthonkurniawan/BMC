<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel app\models\userlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Audit Trail');
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

<div class="bmc-userlog-index">
  <h3><?= Html::encode($this->title) ?></h3>
  <?php Pjax::begin(); ?>
	
  <?=
  GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'filterUrl' => null,
		'tableOptions' => ['class' => 'table table-striped table-bordered list'],
    'columns' => [
      ['class' => 'yii\grid\SerialColumn', 'options'=>['width'=>50]],
      //'date',
      [
        'attribute' => 'date',
        'label' => 'Date',
        'headerOptions' => ['width' => 150],
        'filter' => yii\jui\DatePicker::widget([
          'model' => $searchModel,
          'attribute' => 'date',
          'dateFormat' => 'yyyy-MM-dd',
          'clientOptions' => [
            //'source' => array_keys(app\models\User::find()->orderBy('username')->asArray()->all()),
            'select' => new JsExpression('function( event, ui ) {   //console.log(event, ui);
              this.value = ui.item.value;
              //$("#gridview_ajax").yiiGridView("applyFilter"); 
            }'),
          ],
          'options' => ['id' => 'usersearch-username-autocomplete', 'class' => 'form-control ',],
        ]),
      ],
      //'userid',
      [
        'attribute' => 'username'
        'label' => 'Username',
        'value' => 'user.username',
				'options' => ['width'=>150]
      ],
      'event',
      // [
        // 'class' => 'yii\grid\ActionColumn',
        // 'template' => '{view}',
        // 'contentOptions' => [],
      // ],
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
    // summary default: {begin} {end} {count} {totalCount} {page} {pageCount}
    'summary' => '<div style="margin-bottom:5px;height:21px" class="left">
			<div class="left">
				Showing <b>{begin, number}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, one {item} other {logs}} 
			</div>
			<span class="loader left" style="margin:2px 0px 0px 5px"></span></div>',
  ]);
  ?>
	<div class="right" style="">
		<?php
		echo Html::a('Excel', ['/userlog/print'],['data-pjax'=>"0",'id'=>'print','class'=>'btn btn-outline-primary','style'=>'margin-left:7px',
		'onClick'=>'js:getPrint($(this));']);
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
