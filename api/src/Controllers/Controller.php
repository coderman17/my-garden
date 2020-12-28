<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Models\User;
use MyGarden\Repositories\RepositoryCollection;
use MyGarden\Responses\ResponseInterface;
use MyGarden\Views\ViewInterface;

abstract class Controller
{
    protected User $user;

    protected RepositoryCollection $repositoryCollection;

    protected ResponseInterface $response;

    protected ViewInterface $view;

    /**
     * @param RepositoryCollection $repositoryCollection
     * @param ResponseInterface $response
     * @param ViewInterface $view
     * @throws \Exception
     */
    public function __construct(RepositoryCollection $repositoryCollection, ResponseInterface $response, ViewInterface $view)
    {
        $this->repositoryCollection = $repositoryCollection;

        $this->response = $response;

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