<?php declare(strict_types=1);

namespace Api\Singletone;

use Api\Contracts\Singleton;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;

class Database implements Singleton
{
    private static ?EntityManager $database = null;

    /**
     * @throws ORMException
     * @throws Exception
     */
    public static function getInstance(): EntityManager
    {
        if (self::$database === null) {
            self::$database = (new \Core\Database())->create(
                getenv("DATABASE_NAME"),
                getenv("DATABASE_USER"),
                getenv("DATABASE_PASSWORD"),
                getenv("DATABASE_SERVER"),
                (int) getenv("DATABASE_PORT"),
                __DIR__."/../",
                "pgsql"
            );
        }
        return self::$database;
    }
}