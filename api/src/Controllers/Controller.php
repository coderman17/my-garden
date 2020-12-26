<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
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
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     */
    public function __construct(RepositoryCollection $repositoryCollection, IView $view)
    {
        $this->repositoryCollection = $repositoryCollection;

        $this->user = $this->getUserFromCredentials();

        $this->view = $view;
    }

    //This is a placeholder until I review auth systems properly

    /**
     * @return null|User
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     */
    protected function getUserFromCredentials(): ?User
    {
        return $this->repositoryCollection->userRepository->getUserFromEmailAndPassword('dan@email.com', 'password');
    }
}