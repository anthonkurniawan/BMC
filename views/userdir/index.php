<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserdirSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$user = Yii::$app->user->getIdentity();

//Pjax::begin();
Pjax::begin([
  'id' => 'pjax-con',
  'linkSelector' => '#pjax-con a',
  'formSelector' => '.gridview-filter-form, #user-search, #form-tools',
  'timeout' => 0,
  'enablePushState' => false
]);

$this->title = Yii::t('app', 'User Management');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
getPrint = function(el){ console.log('Print..', el);
	event.preventDefault();
	var href = el[0].href + window.location.search;
	window.location = href; //$('#print').attr('href') console.log(window.location);
}"
  ,$this::POS_READY
);
?>

<style>
  table tr th,
  table tr td {
    padding: 5px !important;
    white-space: nowrap
  }
  .pagination {
    margin: 0
  }
  .table {
    margin-bottom: 10px;
  }
</style>

<div class="userdir-index clear">
  <h3><?= Html::encode($this->title) ?></h3>
  <?php // echo $this->render('_search', ['model' => $searchModel]);  
  ?>

  <?=
  GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    //'emptyCell' =>'<div>-</div>',
    //'emptyText' => 'false',
    'tableOptions' => ['class' => 'table table-striped table-bordered list'],
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
      [
        'attribute' => 'username',
        'label' => 'Username',
        'options' => ['width' => 100]
      ],
      // [
      // 'attribute' => 'name',
      // 'value' => 'profile.name',
      // 'label'=>'Fullname'
      // ],
      'email:email',
      [
        'attribute' => 'dept',
        'value' => 'depts.label',
        'options' => ['width' => 80]
      ],
      [
        'attribute' => 'role',
        'value' => function ($model, $i) {
          return $model->getRoleLabel($model->role);
        },
        'options' => ['width' => 100]
      ],
      [
        'label' => 'Registered',
        'attribute' => 'created_at',
        'value' => 'user.created_at',
        'format' => 'date',
        'contentOptions' => ['style' => 'text-align:center'],
      ],
      [
        'label' => 'Confirmed',
        'attribute' => 'confirmed_at',
        'value' => 'user.confirmed_at',
        'format' => 'date',
        'contentOptions' => ['style' => 'text-align:center'],
      ],
      [
        'label' => 'Blocked',
        'attribute' => 'blocked_at',
        'value' => 'user.blocked_at',
        'format' => 'date',
        'contentOptions' => ['style' => 'text-align:center'],
      ],
      [
        'label' => 'Updated',
        'attribute' => 'updated_at',
        'value' => 'user.updated_at',
        'format' => 'date',
        'contentOptions' => ['style' => 'text-align:center'],
      ],
      [
        'label' => 'Last Login',
        'attribute' => 'last_login_at',
        'contentOptions' => ['style' => 'text-align:center'],
        'value' => function ($model) {
          if (!$model->user || ($model->user && (!$model->user->last_login_at || $model->user->last_login_at == 0))) {
            return null;
            // } else if (extension_loaded('intl')) {
            // //return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->user->last_login_at]);
            // } 
          } else {
            return date('d-m-Y H:m', $model->user->last_login_at);
          }
        }
      ],
      [
        'header' => Yii::t('user', 'Confirmation'),
        'value' => function ($model) {
          if ($model->user) {
            if ($model->user->isConfirmed) {
              return '<div class="text-center"><span class="text-success">' . Yii::t('user', 'Confirmed') . '</span></div>';
            } elseif (!$model->user->isBlocked) {
              return Html::a(Yii::t('user', 'Confirm'), ['user/admin/confirm', 'id' => $model->user->id], [
                'class' => 'btn btn-xs btn-success btn-block',
                'data-method' => 'post',
                'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
              ]);
            }
          }
        },
        'format' => 'raw',
        //'visible' => Yii::$app->getModule('user')->enableConfirmation,
        'contentOptions' => ['style' => 'text-align:center'],
      ],
      [
        'header' => Yii::t('user', 'Block status'),
        'value' => function ($model) {
          if ($model->user) {
            if ($model->user->isBlocked) {
              return Html::a('Unblock', ['user/admin/block', 'id' => $model->user->id], [
                'class' => 'btn btn-xs btn-success btn-block',
                'data-method' => 'post',
                'data-confirm' => 'Are you sure you want to unblock this user?',
              ]);
            } else {
              return Html::a('Block', ['user/admin/block', 'id' => $model->user->id], [
                'class' => 'btn btn-xs btn-danger btn-block',
                'data-method' => 'post',
                'data-confirm' => 'Are you sure you want to block this user?',
              ]);
            }
          }
        },
        'format' => 'raw',
        'contentOptions' => ['style' => 'text-align:center'],
      ],
      [
        'class' => 'yii\grid\ActionColumn',
        'template' => $user && $user->username == 'adminkeren' ? '{update} {delete} {resend_password}' : '{update} {delete}',
        'buttons' => [
          'delete' => function ($url, $model, $key) {
            return Html::a(
              "<span class='glyphicon glyphicon-trash text-primary'></span>",
              $url,
              ['title' => 'Delete', 'data-pjax' => '0', "data-confirm" => "Delete user '$model->username' from the system?"]
            );
          },
          'resend_password' => function ($url, $model, $key) {
            if ($model->user) {
              return Html::a('', ['user/admin/resend-password', 'id' => $model->user->id], [
                'title' => 'Generate and send new password to user',
                'class' => 'glyphicon glyphicon-envelope',
                'data-method' => 'post',
                'data-confirm' => 'Are you sure you want to generate and send new password to this user ?',
              ]);
            }
          },
        ]
      ],
    ],
    'pager' => [
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
				Showing <b>{begin, number}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, one {item} other {Users}} 
			</div>
			<span class="loader left" style="margin:2px 0px 0px 5px"></span></div>
			<p class="right">' . Html::a(Yii::t("app", "Create new user"), ["create"], ["data-pjax" => "0", "class" => "btn btn-outline-primary"]) . '</p>',
  ]);
  ?>
  <div class="right" style="">
    <?php
    echo Html::a('Excel', ['/userdir/print'], ['data-pjax' => "0", 'class' => 'btn btn-outline-primary', 'style' => 'margin-left:7px', 'onclick' => 'js:getPrint($(this));']);
    ?>
  </div>
  <?php Pjax::end(); ?>
</div>

<?php
$this->registerJs("
$(document).ready(function(){

});
"
  //,$this::POS_READY
);
?>
