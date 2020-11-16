<?php

declare(strict_types = 1);

namespace MyGarden\Repositories;

abstract class Repository
{
    protected RepositoryCollection $repositoryCollection;

    public function __construct (RepositoryCollection $repositoryCollection)
    {
        $this->repositoryCollection = $repositoryCollection;
    }
}