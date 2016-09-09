#!/usr/bin/env php
<?php
//Autoload Composer
require_once 'vendor/autoload.php';

//Autoload Prestashop
require_once '../config/config.inc.php';

use Hhennes\PrestashopConsole\PrestashopConsoleApplication;
use Symfony\Component\Yaml\Yaml;

//Load configuration from config.yml
$configuration = Yaml::parse(file_get_contents('config.yml'));

$app = new PrestashopConsoleApplication($configuration['application']['name'], $configuration['application']['version']);

//Add commands from config file
$customCommands = array();
foreach ($configuration['commands'] as $command) {
    $customCommands[] = new $command();
}
$app->addCommands($customCommands);

//Application run
$app->run();