<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Auth\Auth;
use MyGarden\Models\User;
use MyGarden\Interfaces\ResponseInterface;
use MyGarden\Validators\Validator;
use MyGarden\Interfaces\ViewInterface;

abstract class Controller
{
    protected User $user;

    protected ResponseInterface $response;

    protected ViewInterface $view;

    protected Validator $validator;

    /**
     * @param ResponseInterface $response
     * @param ViewInterface $view
     */
    public function __construct(ResponseInterface $response, ViewInterface $view)
    {
        $this->response = $response;

        $this->view = $view;

        $this->validator = $this->getValidator();

        $this->user = Auth::user();
    }

    abstract protected function getValidator(): Validator;
}