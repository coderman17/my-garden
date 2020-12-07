<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Models\User;
use MyGarden\Repositories\RepositoryCollection;
use MyGarden\Response\Response;
use MyGarden\Views\IView;

abstract class Controller
{
    protected User $user;

    protected RepositoryCollection $repositoryCollection;

    protected Response $response;

    protected IView $view;

    public function __construct(RepositoryCollection $repositoryCollection, IView $view)
    {
        $this->repositoryCollection = $repositoryCollection;

        $this->user = $this->getUserFromCredentials();

        $this->view = $view;
    }

    //This is a placeholder until I review auth systems properly
    protected function getUserFromCredentials(): User
    {
        return $this->repositoryCollection->userRepository->getUserFromEmailAndPassword('dan@email.com', 'password');
    }
}