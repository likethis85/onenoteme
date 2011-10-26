<?php

return array(
    'components' => array(
        'log' => array(
            'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'trace, info, error, warning, watch',
				    'categories' => 'system.db.*',
				),
				array(
					'class' => 'CWebLogRoute',
					'levels' => 'trace, info, error, warning, watch',
				    'categories' => 'system.db.*',
				),
            ),
        ),
        'db' => array(
            'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=127.0.0.1; port=3306; dbname=cd_onenote',
			'username' => 'root',
		    'password' => '123',
		    'charset' => 'utf8',
		    'persistent' => true,
		    'tablePrefix' => 'cd_',
            'enableParamLogging' => true,
            'enableProfiling' => true,
        ),
    ),
    
);