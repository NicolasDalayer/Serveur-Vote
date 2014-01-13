<?php

return array
(
	'dsn' => 'mysql:dbname=db;host=127.0.0.2',
	'username' => 'root',
	'password' => '',
	'options' => array
	(
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
	)
);
