<?php

declare(strict_types=1);

namespace MyGarden;

class DatabaseConnection extends Singleton
{
    public function hi()
    {
        echo 'hi from dbconn';
    }
}
