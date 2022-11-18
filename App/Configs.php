<?php

namespace Api;

use Api\Contracts\Singleton;
use Core\Database;
use Doctrine\DBAL\Exception as databaseException;
use Doctrine\ORM\Exception\ORMException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as mailerException;

class Configs implements Singleton {
    private static array $instance = [];

    /**
     * @return array
     * @throws ORMException
     * @throws databaseException
     * @throws mailerException
     */
    public static function getInstance(): array
    {
        if (self::$instance === []) {
            self::$instance["mail"] = new PHPMailer(true);
            self::$instance["mail"]->isSMTP();
            self::$instance["mail"]->Host = getenv("MAIL_SERVER");
            self::$instance["mail"]->SMTPAuth = true;
            self::$instance["mail"]->Username = getenv("MAIL_USER");
            self::$instance["mail"]->Password = getenv("MAIL_PASSWORD");
            self::$instance["mail"]->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            self::$instance["mail"]->Port = 587;
            self::$instance["mail"]->SMTPOptions = [
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            ];
            self::$instance["mail"]->XMailer = "notificator";
            self::$instance["mail"]->CharSet = "utf-8";
            self::$instance["mail"]->setFrom(getenv("MAIL_USER"), explode("@", getenv("MAIL_USER"))[0]);

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