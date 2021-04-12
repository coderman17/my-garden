<?php

declare(strict_types = 1);

require 'vendor/autoload.php';
require '.env.php';

error_reporting(0);

//TODO experiment with moving this error handling into the App and show appropriately for json or not
try {
    $app = new MyGarden\App();

    $app->run();

} catch (\Throwable $throwable) {
    log_throwable($throwable);

    $previous = $throwable->getPrevious();

    while($previous !== null) {
        log_throwable($previous);

        $previous = $previous->getPrevious();
    }

    header('Content-Type: application/json');

    $code = ($throwable->getCode() === 0) ? 500 : $throwable->getCode();

    $message = ($code === 500) ? 'Internal Server Error' : $throwable->getMessage();

    http_response_code($code);

    $error = [];

    $error['error'] = [
        'code' => $code,
        'message' => $message
    ];

    echo json_encode($error);
}

function log_throwable(Throwable $throwable): void
{
    error_log("Exception: " . $throwable->getMessage() . "\nStack trace:\n" . $throwable->getTraceAsString() . "\n  thrown in " . $throwable->getFile() . " on line " . $throwable->getLine());
}
