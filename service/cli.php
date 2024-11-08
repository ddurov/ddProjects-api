<?php

require_once "vendor/autoload.php";

use Core\Database;
use Core\Tools;

try {
	Database::getInstance()->executeCLI();
} catch (\Doctrine\DBAL\Exception $e) {
	Tools::log(1, "CLI error: {$e->getMessage()}\n");
	die(1);
}