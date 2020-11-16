<?php

declare(strict_types = 1);

namespace MyGarden\Controllers;

use MyGarden\Repositories\RepositoryCollection;

abstract class Controller
{
    protected RepositoryCollection $repositoryCollection;

    public function __construct(RepositoryCollection $repositoryCollection)
    {
        $this->repositoryCollection = $repositoryCollection;
    }
}