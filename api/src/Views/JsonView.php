<?php

declare(strict_types = 1);

namespace MyGarden\Views;

use MyGarden\Responses\ResponseInterface;

class JsonView implements ViewInterface
{
    public function display(ResponseInterface $response): void
    {
        header('content-type: application/json');

        http_response_code($response->getCode());

        echo json_encode($response->getBody());
    }
}