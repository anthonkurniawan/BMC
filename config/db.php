<?php
return [
	'local'=> [
		'class' => 'yii\db\Connection',
		'dsn' => 'sqlsrv:Server=LNV-WIN10\SQLEXPRESS;Database=bmc',
		'username' => '',
		'password' => '',
		'charset' => 'utf8',

		// Schema cache options (for production environment)
		'enableSchemaCache' => true,
		'schemaCacheDuration' => 3600,
		'schemaCache' => 'cache',
		'enableSlaves' =>false,
	],
];
