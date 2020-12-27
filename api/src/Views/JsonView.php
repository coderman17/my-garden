<?php

declare(strict_types = 1);

namespace MyGarden\Views;

use MyGarden\Response\Response;

class JsonView implements IView
{
    public function display(Response $response): void
    {
        header('content-type: application/json');

        http_response_code($response->code);

        echo json_encode($response->body);
    }
}