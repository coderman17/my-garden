<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Exceptions\OutOfRangeInt;
use MyGarden\Exceptions\OverMaxChars;
use MyGarden\Exceptions\UnderMinChars;
use MyGarden\Models\User;
use MyGarden\Repositories\RepositoryCollection;
use MyGarden\Responses\ResponseInterface;
use MyGarden\Validators\Validator;
use MyGarden\Views\ViewInterface;

abstract class Controller
{
    protected User $user;

    protected RepositoryCollection $repositoryCollection;

    protected ResponseInterface $response;

    protected ViewInterface $view;

    protected Validator $validator;

    /**
     * @param RepositoryCollection $repositoryCollection
     * @param ResponseInterface $response
     * @param ViewInterface $view
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    public function __construct(RepositoryCollection $repositoryCollection, ResponseInterface $response, ViewInterface $view)
    {
        $this->repositoryCollection = $repositoryCollection;

        $this->response = $response;

        $this->user = $this->getUserFromCredentials();

        $this->view = $view;

        $this->validator = $this->getValidator();
    }

    abstract protected function getValidator(): Validator;

    //This is a placeholder until I review auth systems properly

    /**
     * @return User
     * @throws OutOfRangeInt
     * @throws OverMaxChars
     * @throws UnderMinChars
     * @throws \Exception
     */
    protected function getUserFromCredentials(): User
    {
//        return $this->repositoryCollection->userRepository->getUserFromEmailAndPassword(
//            'dan@email.com',
//            'password'
//        );
        return new User(1, 'dan', 'dan@email.com', 'password');
    }
}