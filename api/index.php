<?php

declare(strict_types = 1);

require 'vendor/autoload.php';

function exception_handler(object $exception): void
{
    error_log("Exception: " . $exception->getMessage() . "\nStack trace:\n" . $exception->getTraceAsString() . "\n  thrown in " . $exception->getFile() . " on line " . $exception->getLine());

    header('Content-Type: application/json');

    $code = ($exception->getCode() === 0) ? 500 : $exception->getCode();

    $message = $exception->publicMessage ?? 'Internal Server Error';

    http_response_code($code);

    $error = [];

    $error['error'] = [
        'code' => $code,
        'message' => $message
    ];

    echo json_encode($error);
}

error_reporting(0);

set_exception_handler('exception_handler');

$app = new MyGarden\App();

$app->run();
