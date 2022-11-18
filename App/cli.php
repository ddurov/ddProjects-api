<?php declare(strict_types=1);

require_once "vendor/autoload.php";

use Api\Configs;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

ConsoleRunner::run(new SingleManagerProvider(Configs::getInstance()["masterDatabase"]));

