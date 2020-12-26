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

    /**
     * @param RepositoryCollection $repositoryCollection
     * @param IView $view
     * @throws \Exception
     */
    public function __construct(RepositoryCollection $repositoryCollection, IView $view)
    {
        $this->repositoryCollection = $repositoryCollection;

        $this->user = $this->getUserFromCredentials();

        $this->view = $view;
    }

    //This is a placeholder until I review auth systems properly

    /**
     * @return User
     * @throws \Exception
     */
    protected function getUserFromCredentials(): User
    {
        $user = $this->repositoryCollection->userRepository->getUserFromEmailAndPassword('dan@email.com', 'password');

        if (!$user instanceof User){
            throw new \Exception('Could not find user from credentials');
        }

        return $user;
    }
}