<?php

declare(strict_types = 1);

namespace MyGarden\Views;

use MyGarden\Responses\ResponseInterface;

interface ViewInterface
{
    /**
     * @param ResponseInterface $response
     * @throws \Exception
     */
    public function display(ResponseInterface $response): void;
}