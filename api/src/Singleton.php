<?php

declare(strict_types=1);

namespace MyGarden;

abstract class Singleton
{
    /**
     * @var array<class-string, Singleton>
     */
    private static array $instances = [];

    public static function getInstance(): static
    {
        if (!isset(self::$instances[static::class])) {
            static::$instances[static::class] = new static();
        }

        return static::$instances[static::class];
    }

    final protected function __construct()
    {
        //
    }

    public function hi()
    {
        echo 'hi from singleton';
    }
}
