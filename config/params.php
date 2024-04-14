<?php
return [
	'adminEmail'=>'bmc@taisho-support.co.id',
	'rules'=>[
		'username'=>[
			'usernameTrim' => ['username', 'trim'],
			#'usernameRequired' => ['username', 'required', 'on' => ['register', 'create', 'connect', 'update']],
			'usernameRequired' => ['username', 'required', 'on' => ['create', 'connect', 'update']],
			'usernameMatch' => ['username', 'match', 'pattern' =>'/^[-a-zA-Z0-9_\.@]+$/'],
			'usernameLength' => ['username', 'string', 'min' => 8, 'max' => 30],
			'usernameUnique' => [
				'username',
				'unique',
				'message' => \Yii::t('user', 'This username has already been taken')
			],
		],
		'email'=>[
			'emailTrim' => ['email', 'trim'],
			'emailRequired' => ['email', 'required', 'on' => ['register', 'connect', 'create', 'update']],
			'emailPattern' => ['email', 'email'],
			'emailLength' => ['email', 'string', 'max' => 100],
			'emailUniqueCreate' => ['email','unique','on'=>['create'],
				'message' => \Yii::t('user', 'This email address has already been taken')
			],
			'emailUniqueRegister' => [
				'email',
				'unique',
				'targetClass'=>'dektrium\user\models\User', 
				'on'=>['register'],
				'message' => \Yii::t('user', 'This email address has already been registered')
			],
			'emailExist' => [
				'email', 
				'exist', 
				'targetClass' =>'app\models\userdir', 
				'on'=>['register'],
				'targetAttribute' => 'email',
				'message' => Yii::t('user', 'Your email not allowed to register into this system. please contact your administrator')],
		],
		'password'=>[
			'passwordRequired' => [['password','passwordConfirm'], 'required', 'on' => ['register']],
			'passwordLength' => ['password', 'string', 'min' => 8, 'max' => 30, 'on' => ['register', 'create']],
			'passwordMatch' => [['password', 'passwordConfirm'], 'match', 'pattern' => '/^(?=.*\d).{8,16}$/',
				'message' => Yii::t('user', 'Password harus mangandung huruf dan numeric'), 'on' => ['register']],
			'passwordCompare' => ['passwordConfirm', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match", 'on' => ['register']],
		]
	],
	'db_engine'=>'mssql', // mysql
];
