<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
//use yii\widgets\Pjax;

$this->registerJs("
function loadScript(url, callback){
   var script = document.createElement('script')
   script.type = 'text/javascript';
   if (script.readyState){  //IE
    script.onreadystatechange = function(){
      if (script.readyState == 'loaded' ||
        script.readyState == 'complete'){
        script.onreadystatechange = null;
        callback();
      }
    };
  } else {  //Others
		script.onload = function(){
      callback();
    };
  }
  script.src = url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

var loc = window.location;
var path = loc.protocol+'//'+loc.hostname+'/bmc2/';
var oldBrowser = false;
var browser = navigator.userAgent.toLowerCase();
var ieCheck = /msie [\w.]+/.exec(browser);

if(ieCheck){
	var ver = /\d+/.exec(ieCheck[0]);  console.log('IE:', ieCheck[0], 'ver:', ver);
	if(ver < 9) oldBrowser = true;
}

if(oldBrowser){ 
	alert('Browser Not Supported');
	path = path+'jquery-1.9.1.min.js';
	loadScript(path, function(){});
	
	//var x = document.querySelectorAll('[src=\"/bmc2/assets/1f90926f/jquery.js\"]');
	//x[0].parentNode.removeChild(x[0]);
}
else path = path+'jquery.js';
//loadScript(path, function(){});
", $this::POS_HEAD);
AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="<?=Yii::$app->homeUrl?>/favicon.ico" type="image/vnd.microsoft.icon"></link>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
  </head>
  <body>
    <?php $this->beginBody() ?>

    <div class="wrap">
      <?php
      $isAdmin = 0; 
			$isGuest = 1; 
			$identity = \Yii::$app->session->get('identity'); 
			$activemenu = '';
			if($identity){
				$isAdmin = $identity['isAdmin'];
				$isGuest = $identity['isGuest'];
				$activemenu = $identity['activeMenuDept_name'];
				echo Html::HiddenInput('isAdmin', $identity['isAdmin'], ['id'=>'isAdmin']);
				echo Html::HiddenInput('xdept', $identity['activeMenuDept_id'], ['id'=>'xdept']);
			}
			echo Html::HiddenInput('isGuest', $isGuest, ['id'=>'isGuest']);
			
			NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
					'id'=>'menu',
          'class' => 'navbar-inverse navbar-fixed-top',
        ],
				'brandOptions'=>['class'=>'active', 'style'=>'font-weight:bold;padding-right:0;'],
				'containerOptions'=>[],
				'innerContainerOptions'=>[],
      ]);
			echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left', 'style'=>'border-right:1px solid #585252'], // ul
        'items' => [
					[
						'label' => $activemenu,
						//'url' => ['/report/vst'], 
						'linkOptions'=>['style'=>'text-align:right;font-weight:bold;padding-left:0px;color:#e6dede;font-size:15px'], // li
						'options'=>['class'=>'active', 'style'=>'width:119px'],
						'visible' => !$isGuest && !$isAdmin
					],
					[
            'label' => $activemenu,
						'linkOptions'=>['style'=>'text-align:right'],
						'options'=>['id'=>'menu-dept', 'class'=>'active', 'style'=>'width:124.5px'],
            'items' => [
							['label' => 'Production', 'url' =>'#', 'options'=>[]],
							['label' => 'Warehouse', 'url' =>'#'],
							['label' => 'Quality', 'url' =>'#'],
							['label' => 'Engineering', 'url' =>'#'],
							['label' => 'Test', 'url' =>'#'],
            ],
            'visible' => $isAdmin
          ],
				],
      ]);
      echo Nav::widget([
        'options' => ['id'=>'area-list', 'class' => 'navbar-nav navbar-left'],
				'items' => [],
      ]);
      echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right','style'=>'border-left:1px solid #585252'],
        'items' => [
          [
            'label' => 'Admin',
						'options'=>['id'=>'admin'],
            'items' => [
							['label' => 'User Management', 'url' => ['/userdir/index']],
							['label' => 'Tag Management', 'url' => ['/tagname/index']],
              ['label' => 'Audit Trail', 'url' => ['/userlog/index']],
							['label' => 'User Admin', 'url' => ['/user/admin']],
            ],
            'visible' => $isAdmin
          ],
          [
            'label' => 'Profiles',
            'items' => [
              ['label' => 'My profile', 'url' => ['/user/profile'], 'visible' => !$isGuest],
              ['label' => 'Settings', 'url' => ['/user/settings/profile'], 'visible' => !$isGuest],
            ],
            'visible' => !$isGuest //&& $id->isAdmin
          ],
          $isGuest ?
            ['label'=>'Sign in', 'url'=>['/user/security/login']] :
							['label'=>'Sign out (' . $identity['username'] . ')', 'url'=>['/user/security/logout'],'linkOptions'=>['data-method'=>'post']],
						['label'=>'Register', 'url'=>['/user/registration/register'], 'visible'=>$isGuest]
        ],
      ]);
			echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right', 'id'=>'sum'],
        'items' => [
					['label' => 'Summary', 'url' => ['/summary/warehouse'], 'options'=>['id'=>'summary', 'class'=>'right', 'style'=>'display:none']],
				],
      ]);
      NavBar::end();
      ?>

      <div class="container">
        <?=
        Breadcrumbs::widget([
          'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
					'options'=> ['class' => 'breadcrumb right']
        ])
        ?>
				<div id="msg-con" class="left alert alert-warning alert-dismissible" role="alert">
					<button type="button" class="close" onclick="bms.dismisMsg();"><span aria-hidden="true">&times;</span></button>
					<div class="msg left" style="margin-left:5px"></div>
				</div>

        <?= Alert::widget(['options'=>['class'=>'left', 'style'=>'width:70%']]) ?>
				
				<?= $content ?>

      </div>
    </div>

    <footer class="footer">
      <div class="container left">
        <p class="pull-left">&copy; PT.Taisho Pharmaceutical Indonesia TBK. <?= date('Y') ?></p>
      </div>
    </footer>

<?php $this->endBody() ?>
  </body>
</html>
<?php $this->endPage() ?>
