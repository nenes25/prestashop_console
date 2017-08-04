<?php
/**
 * Prestashop Console
 * Configuration File
 */
$configuration = array();

/**
 * Application Information
 * Do not touch
 */
$configuration['application'] = array(
    'name' => 'PrestashopConsole',
    'version' => '0.9.0',
    'author' => 'hhennes <contact@h-hennes.fr>',
    'contributors' => array(
        'okom3pom',
        'lutek'
    )
);

/**
 * Console Commands
 * Add your new commands in the end of the array
 */
$configuration['commands'] = array(
  'Hhennes\PrestashopConsole\Command\Module\EnableCommand',
     'Hhennes\PrestashopConsole\Command\Module\DisableCommand',
     'Hhennes\PrestashopConsole\Command\Module\ListCommand',
     'Hhennes\PrestashopConsole\Command\Module\InstallCommand',
     'Hhennes\PrestashopConsole\Command\Module\UninstallCommand',
     'Hhennes\PrestashopConsole\Command\Module\ResetCommand',
     'Hhennes\PrestashopConsole\Command\Module\Hook\AddModuleHooksCommand',
     'Hhennes\PrestashopConsole\Command\Module\Hook\RemoveModuleHooksCommand',
     'Hhennes\PrestashopConsole\Command\Module\Hook\ListModuleHooksCommand',
     'Hhennes\PrestashopConsole\Command\Cache\CleanCommand',
     'Hhennes\PrestashopConsole\Command\Cache\FlushCommand',
     'Hhennes\PrestashopConsole\Command\Cache\MediaCommand',
     'Hhennes\PrestashopConsole\Command\Cache\Smarty\ClearCommand',
     'Hhennes\PrestashopConsole\Command\Cache\Smarty\ConfigureCommand',
     'Hhennes\PrestashopConsole\Command\Configuration\GetCommand',
     'Hhennes\PrestashopConsole\Command\Configuration\GetAllCommand',
     'Hhennes\PrestashopConsole\Command\Configuration\SetCommand',
     'Hhennes\PrestashopConsole\Command\Configuration\DeleteCommand',
     'Hhennes\PrestashopConsole\Command\Configuration\MassCommand',
     'Hhennes\PrestashopConsole\Command\Preferences\Search\IndexCommand',
     'Hhennes\PrestashopConsole\Command\Preferences\CmsCommand',
     'Hhennes\PrestashopConsole\Command\Preferences\CmsCategoryCommand',
     'Hhennes\PrestashopConsole\Command\Preferences\MaintenanceCommand',
     'Hhennes\PrestashopConsole\Command\Preferences\UrlRewriteCommand',
     'Hhennes\PrestashopConsole\Command\Preferences\OverrideCommand',
     'Hhennes\PrestashopConsole\Command\Dev\ListOverridesCommand',
     'Hhennes\PrestashopConsole\Command\Dev\AddIndexFilesCommand',
     'Hhennes\PrestashopConsole\Command\Dev\IdeClassNamesCommand',
     'Hhennes\PrestashopConsole\Command\Install\InstallCommand',
     'Hhennes\PrestashopConsole\Command\Admin\User\CreateCommand',
     'Hhennes\PrestashopConsole\Command\Admin\User\PasswordCommand',
);

