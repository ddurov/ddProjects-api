<?php declare(strict_types=1);

namespace Api\Contracts;

interface Singleton
{
    /**
     * @return static
     */
    public static function getInstance();
}