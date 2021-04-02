<?php

declare(strict_types = 1);

namespace MyGarden\Interfaces;

interface ViewInterface
{
    /**
     * @param ResponseInterface $response
     * @throws \Exception
     */
    public function display(ResponseInterface $response): void;
}