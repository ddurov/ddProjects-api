<?php

namespace Api;

use Api\Contracts\Singleton;
use Core\Database;
use Doctrine\DBAL\Exception as databaseException;
use Doctrine\ORM\Exception\ORMException;

class Configs implements Singleton {
    private static array $instance = [];

    /**
     * @return array
     * @throws ORMException
     * @throws databaseException
     */
    public static function getInstance(): array
    {
        if (self::$instance === []) {
            self::$instance["masterDatabase"] = (new Database())->create(
                "general",
                getenv("DATABASE_LOGIN"),
                getenv("DATABASE_PASSWORD"),
                getenv("DATABASE_SERVER"),
                __DIR__
            );
        }
        return self::$instance;
    }
}