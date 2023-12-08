<?php

class Singleton
{
    protected static self|null $instance = null;

    final private function __construct()
    {
    }

    public static function getInstance(): static
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}