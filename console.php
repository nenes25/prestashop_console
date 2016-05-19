#!/usr/bin/env php
<?php
//Autoload Composer
require_once 'vendor/autoload.php';

//Autoload Prestashop
require_once '../config/config.inc.php';

use Hhennes\PrestashopConsole\PrestashopConsoleApplication;
use Symfony\Component\Yaml\Yaml;

//Chargement de la configuration depuis le fichier config.yml
$configuration = Yaml::parse(file_get_contents('config.yml'));

$app = new PrestashopConsoleApplication($configuration['application']['name'], $configuration['application']['version']);

//Insertion des commandes personnalisées depuis le fichier de configuration
$customCommands = array();
foreach ($configuration['commands'] as $command) {
    $customCommands[] = new $command();
}
$app->addCommands($customCommands);

//Lancement de l'application
$app->run();

/*
  Liste des commandes à implémenter
  //Debug
  //Disable Non prestashopModules
  //Disable all overrides
  //Smarty
  //Template compilation
  //Cache
  //Caching Type
  //ClearCacheConfig
  //ClearCache
 // Enable / Disable url rewrite
  /**
 * Smarty: vider le cache / activer/desactiver / forcer la compilation
 * Thèmes : purger css / js
 */

