<?php

declare(strict_types=1);

namespace MyGarden;

abstract class Singleton
{
    /**
     * @var array<class-string, Singleton>
     */
    private static array $instances = [];

    public static function getInstance(): Singleton
    {
        if (!isset(self::$instances[static::class])) {
            static::$instances[static::class] = new static();

            static::$instances[static::class]->init();
        }

        return self::$instances[static::class];
    }

    abstract protected function init(): void;

    final private function __construct()
    {
        //
    }
}
