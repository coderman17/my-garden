<?php

declare(strict_types = 1);

namespace MyGarden\Models;

use MyGarden\Repositories\RepositoryCollection;

abstract class Model
{
    protected function validate(array $items): void
    {
        foreach ($items as $item){
            if (!$item[1]){
                throw new \Exception($item[0] . " has failed validation");
            }
        }
    }
}