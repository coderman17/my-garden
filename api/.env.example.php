<?php

declare(strict_types = 1);

namespace MyGarden;

$variables = [
    'APP_DOMAIN' => "localhost",
    'DB_PORT' => '3306',
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'my_garden',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '...',
];

foreach ($variables as $key => $value) {
	putenv("$key=$value");
}
