#!/usr/bin/env php
<?php
/**
 * 2007-2018 Hennes Hervé
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@h-hennes.fr so we can send you a copy immediately.
 *
 * @author    Hennes Hervé <contact@h-hennes.fr>
 * @copyright 2007-2018 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

use Hhennes\PrestashopConsole\PrestashopConsoleApplication;

//Autoload Composer
require_once 'src/vendor/autoload.php';

//Console Application
require_once 'config.php';

// Only allow execution from the console script directory (To have use of the current path to get to the prestashop instance)
// We consider that the console directory must be located at the root of the prestashop instance so we can get to the root by 
// going one lever upper (../ <- Root of prestashop instance if exists)
if (getcwd() !== __DIR__) {
    echo "Error :\n";
    echo "This script must be executed only from its directory to have correct behaviour in php mode.\n";
    echo "Please change directory to " . __DIR__ . " and execute the script again.\n";
    exit(1);
}

$app = new PrestashopConsoleApplication($configuration['application']['name'], $configuration['application']['version']);

//Autoload Prestashop
if ( is_file('../config/config.inc.php')) {
    include_once '../config/config.inc.php';

    //Get App declared commands
    $app->getDeclaredCommands();
}
//If no prestashop conf find, only allow to install Prestashop
else {
    $app->registerInstallCommands();
    $app->setDefaultCommand('install:info');
}

//Application run
$app->run();
