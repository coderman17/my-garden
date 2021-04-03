<?php

declare(strict_types=1);

namespace MyGarden;

class OtherSingleThing extends Singleton
{
    public function hi()
    {
        echo 'hi from other';
    }
}
