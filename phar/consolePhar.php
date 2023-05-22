#!/usr/bin/env php
<?php
/**
 * 2007-2020 Hennes Hervé
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
 * @copyright 2007-2020 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

use PrestashopConsole\PrestashopConsoleApplication;

//Autoload Composer
require_once 'src/vendor/autoload.php';

//Console Application
require_once 'config.php';
$app = new PrestashopConsoleApplication($configuration['application']['name'], $configuration['application']['version']);
// This script is contained in bin/phar directory so we need to get 2 level upper for the root directory
$app->initializeForPharExecution(__DIR__);

//Autoload Prestashop
if ( is_file('config/config.inc.php')) {
    include_once 'config/config.inc.php';
    $app->getDeclaredCommands();
}
//If no prestashop conf find, only allow to install Prestashop
else {
    $app->registerInstallCommands();
    $app->setDefaultCommand('install:info');
}

//Application run
$app->run();