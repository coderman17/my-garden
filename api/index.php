<?php

declare(strict_types = 1);

require 'vendor/autoload.php';

function exception_handler(object $exception): void
{
    error_log("Exception: " . $exception->getMessage() . "\nStack trace:\n" . $exception->getTraceAsString() . "\n  thrown in " . $exception->getFile() . " on line " . $exception->getLine());

    header('Content-Type: application/json');

    http_response_code(500);
}

set_exception_handler('exception_handler');

$app = new MyGarden\App();

$app->run();
