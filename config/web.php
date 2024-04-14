<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
  'id' => 'bmc2',
  'basePath' => dirname(__DIR__),
  'name' => 'BMC2',
  'timeZone' => "Asia/Jakarta",
  'language' => 'en',
  //'bootstrap' => ['log'],
  'aliases' => [
    '@bower' => '@vendor/bower-asset',
    '@npm' => '@vendor/npm-asset',
  ],
  'components' => [
    'assetManager' => [
      'linkAssets' => false, // make sym-link to assets, instead to hard-copy the asset file
      'appendTimestamp' => true, // refresh file chache from modified time
    ],
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ],
    'errorHandler' => [
      'errorAction' => 'site/error',
    ],
    'log' => [
      'traceLevel' => YII_DEBUG ? 3 : 0,
      'targets' => [
        [
          'class' => 'yii\log\FileTarget',
          'levels' => ['error', 'warning'],
        ],
      ],
    ],
    'mailer' => [
      'class' => 'yii\swiftmailer\Mailer',
      //'viewPath' => '@backend/mail',
      'useFileTransport' => false, //set this property to false to send mails to real email addresses
      //comment the following array to send mail using php's mail function
			/*
			Gmail SMTP server address: smtp.gmail.com
			Gmail SMTP username: Your full Gmail address (e.g. yourusername@gmail.com)
			Gmail SMTP password: Your Gmail password
			Gmail SMTP port (TLS): 587
			Gmail SMTP port (SSL): 465
			Gmail SMTP TLS/SSL required: yes
			*/
      'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
				'username'=>'',
				'password'=>'',
        'port' => '587',
        'encryption' => 'tls',
        'streamOptions'=>[
          'ssl'=>[
						'verify_peer'=>false,
						'verify_peer_name'=>false,
						'allow_self_signed'=>true
          ]
        ]
      ],
    ],
    'db' => $db['local'],
    'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'rules' => [
        'docs' => 'site/docs',
        'docs/<title:[\w_-]+>' => 'site/docs',
        '<controller:\w+>s' => '<controller>/index',
				'report/<unit>' => 'report/index',
				'report/<unit>/<date>' => 'report/index',
				'report/<unit>/<date>/<print>' => 'report/index',
				'summary/<unit>' => 'summary/index',
        'api/trend/<unit>' =>'api/trend',
        'historian/report/<unit>' =>'historian/report',
        'historian/report-detail/<unit>' =>'historian/report-detail',
      ]
    ],
    'authManager' => [
			'class' => 'yii\rbac\PhpManager',
    ],
    'user' => [
      //'identityClass' => 'app\models\User',
      'enableAutoLogin' => false,
			'authTimeout'=>3600,  // in sec
			'loginUrl'=>'/bmc2/user/login',
      'identityClass' => 'dektrium\user\models\User', // If you need to have custom user component, then you should configure it to use Yii2-user identity class:
			'on afterLogin' => ['app\controllers\LogController', 'setSession'],
			'on afterLogout' => function($event){
				Yii::$app->session->remove('identity');
			},
			'identityCookie' => [
        'name' => '_identity',
        'httpOnly' => true,
        'domain' => 'localhost',
        'path'=>'/bmc2/'
      ],
		],
		'session' => [
			'name' => 'BMCYII2SESS',
			'cookieParams' => [
        'domain' => 'localhost',
        'path'=>'/bmc2/',
        'httpOnly' => true,
      ],
    ],
    'request' => [
      // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
      'cookieValidationKey' => '0zzjrLGT3IDmkooaAKNccW3blnjBvL3U',
    ],
    'view' => [
      'theme' => [
        'pathMap' => [
          '@dektrium/user/views' => '@app/views/user'
        ],
      ],
    ],
		'formatter' => [
      'class' => 'yii\i18n\Formatter',
      'nullDisplay' => '-',
			'dateFormat' => 'dd-MM-yyyy',
    ],
  ],
  'bootstrap' => ['gii'],
  'modules' => [
    'gii' => 'yii\gii\Module',
    'user' => [
      'class' => 'dektrium\user\Module',
    ],
  ],
  'params' => $params,
];

if (YII_ENV_DEV) {  
  // configuration adjustments for 'dev' environment
  $config['bootstrap'][] = 'debug';
  $config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
      // uncomment the following to add your IP if you are not connecting from localhost.
      //'allowedIPs' => ['127.0.0.1', '::1'],
  ];

  $config['bootstrap'][] = 'gii';
  $config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
      // uncomment the following to add your IP if you are not connecting from localhost.
      //'allowedIPs' => ['127.0.0.1', '::1'],
  ];
}

return $config;
