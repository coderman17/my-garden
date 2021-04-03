<?php

declare(strict_types=1);

namespace MyGarden\Views;

use MyGarden\Interfaces\ResponseInterface;
use MyGarden\Interfaces\ViewInterface;

class JsonView implements ViewInterface
{
    /*
     * @inheritDoc
     */
    public function display(ResponseInterface $response): void
    {
        header('content-type: application/json');

        http_response_code($response->getCode());

        echo json_encode($response->getBody(), JSON_THROW_ON_ERROR);
    }
}
