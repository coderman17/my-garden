<?php

declare(strict_types = 1);

namespace MyGarden\Response;

class Response
{
    public int $code;

    /**
     * @var mixed
     */
    public $body;

    /**
     * @param int $code
     * @param mixed $body
     */
    public function __construct (int $code, $body)
    {
        $this->code = $code;

        $this->body = $body;
    }
}