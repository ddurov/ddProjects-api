<?php

require_once "vendor/autoload.php";

use Core\Database;
use Core\Tools;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;

try {
	Database::getInstance()->executeCLI();
} catch (\Doctrine\DBAL\Exception|MissingMappingDriverImplementation $e) {
	Tools::log(1, "CLI error: {$e->getMessage()}\n");
	die(0);
}