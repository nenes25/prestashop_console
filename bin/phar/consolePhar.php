#!/usr/bin/env php
<?php
//Autoload Composer
require_once 'vendor/autoload.php';

//Autoload Prestashop
require_once 'config/config.inc.php';

use Hhennes\PrestashopConsole\PrestashopConsoleApplication;
use Symfony\Component\Yaml\Yaml;
/**
 * @ToDO : Make configuration working in phar
 */
$app = new PrestashopConsoleApplication('PrestashopConsole', '0.2.0');

//Add commands from config file
$configCommands = array(
     'Hhennes\PrestashopConsole\Command\Module\EnableCommand',
     'Hhennes\PrestashopConsole\Command\Module\DisableCommand',
     'Hhennes\PrestashopConsole\Command\Module\ListCommand',
     'Hhennes\PrestashopConsole\Command\Module\InstallCommand',
     'Hhennes\PrestashopConsole\Command\Module\UninstallCommand',
     'Hhennes\PrestashopConsole\Command\Module\ResetCommand',
     'Hhennes\PrestashopConsole\Command\Cache\ClearCommand',
     'Hhennes\PrestashopConsole\Command\Cache\Smarty\ClearCommand',
     'Hhennes\PrestashopConsole\Command\Cache\Smarty\ConfigureCommand',
     'Hhennes\PrestashopConsole\Command\Configuration\GetCommand',
     'Hhennes\PrestashopConsole\Command\Configuration\SetCommand',
     'Hhennes\PrestashopConsole\Command\Configuration\DeleteCommand',
     'Hhennes\PrestashopConsole\Command\Preferences\Search\IndexCommand',
     'Hhennes\PrestashopConsole\Command\Preferences\CmsCommand',
     'Hhennes\PrestashopConsole\Command\Preferences\CmsCategoryCommand',
     'Hhennes\PrestashopConsole\Command\Preferences\MaintenanceCommand',
     'Hhennes\PrestashopConsole\Command\Preferences\UrlRewriteCommand',
     'Hhennes\PrestashopConsole\Command\Preferences\OverrideCommand',
     'Hhennes\PrestashopConsole\Command\Dev\ListOverridesCommand',
    );

$customCommands = array();

foreach ($configCommands as $command) {
    $customCommand[] = new $command();
}
$app->addCommands($customCommand);

//Application run
$app->run();