<?php

declare(strict_types = 1);

require 'vendor/autoload.php';
require '.env.php';

error_reporting(0);

$app = new MyGarden\App();

try {
    $app->run();

} catch (\Throwable $exception) {
    //TODO move this error handling into the app and show appropriately for json or not
    error_log("Exception: " . $exception->getMessage() . "\nStack trace:\n" . $exception->getTraceAsString() . "\n  thrown in " . $exception->getFile() . " on line " . $exception->getLine());

    header('Content-Type: application/json');

    $code = ($exception->getCode() === 0) ? 500 : $exception->getCode();

    $message = ($code === 500) ? 'Internal Server Error' : $exception->getMessage();

    http_response_code($code);

    $error = [];

    $error['error'] = [
        'code' => $code,
        'message' => $message
    ];

    echo json_encode($error);
}
