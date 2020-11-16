<?php

declare(strict_types = 1);

namespace MyGarden;

$variables = [
	'APP_DOMAIN' => "localhost",
    'DB_PORT' => '3306',
    'DB_HOST' => 'mysql',
    'DB_NAME' => 'mygarden',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => 'password',
];

foreach ($variables as $key => $value) {
	putenv("$key=$value");
}
