<?php
/**
 * 2007-2019 Hennes Hervé
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
 * @copyright 2007-2019 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Finder\Finder;

class PrestashopConsoleApplication extends BaseApplication
{
    const APP_NAME = 'prestashopConsole';
    // Execution of the console from a phar archive
    const EXECUTION_MODE_PHAR = 'phar';
    // Namespace of the Commands classes
    const COMMANDS_NAMESPACE = 'PrestashopConsole\\Command';

    /** @var string php|phar Console run mod */
    protected $_runAs = 'php';

    /** @var string Phar archive root location */
    protected $_pharArchiveRootLocation = null;

    /**
     * Set RunAs Mode
     *
     * @param string $mode
     *
     * @return void
     */
    public function setRunAs($mode): void
    {
        $this->_runAs = $mode;
    }

    /**
     * Get RunAs
     *
     * @return string
     */
    public function getRunAs()
    {
        return $this->_runAs;
    }

    /**
     * Initialize the console application for an execution in phar mode.
     *
     * @param string $archiveLocation : The location of the phar archive currently executed
     *
     * @return void
     *
     * @throws \Exception
     */
    public function initializeForPharExecution($archiveLocation): void
    {
        // Assert that the given path is a file in the file system.
        if (!file_exists($archiveLocation)) {
            throw new \Exception('The given phar archive location is not a file : ' . $archiveLocation);
        }
        // Assert that the location starts with the PHAR prefix
        if (0 !== strpos($archiveLocation, 'phar://')) {
            throw new \Exception('The given phar archive location is not a phar archive path (It must start with phar://) : ' . $archiveLocation);
        }
        $this->_runAs = PrestashopConsoleApplication::EXECUTION_MODE_PHAR;
        $this->_pharArchiveRootLocation = $archiveLocation.'/../../';
    }

    /**
     * Automatically register all existing commands
     *
     * @return void
     */
    public function getDeclaredCommands(): void
    {
        $this->registerCommands();
    }

    /**
     * Register only the installation commands.
     *
     * @return void
     */
    public function registerInstallCommands(): void
    {
        $this->registerCommands('install');
    }

    /**
     * Register commands in the application with an optionnal filter on the namespace.
     * At the moment, the namespace is an actual file namespace (The directory in which the commands scripts are declared)
     *
     * @param string $commandNamespace : (OPTIONNAL) The name of the namespace for the commands to register
     *
     * @return void
     */
    protected function registerCommands($commandNamespace = null): void
    {
        // The root of the search depends on the run mode
        $dir = ($this->_runAs == PrestashopConsoleApplication::EXECUTION_MODE_PHAR) ? $this->_pharArchiveRootLocation : getcwd();
        // Source directory
        $dir .= DIRECTORY_SEPARATOR . 'src';

        $commandsSearchNamespace = PrestashopConsoleApplication::COMMANDS_NAMESPACE;
        // Add the namespace to the search directory path
        if (null !== $commandNamespace) {
            $commandsSearchNamespace .= '\\' . ucwords($commandNamespace);
        }

        $commandFilepaths = Finder::create()->files()
            ->name('*Command.php')
            ->in($dir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $commandsSearchNamespace));

        if (sizeof($commandFilepaths)) {
            $customCommands = [];
            foreach ($commandFilepaths as $command) {
                $classPath = $commandsSearchNamespace . '\\' . str_replace(
                    DIRECTORY_SEPARATOR,
                    '\\',
                    $command->getRelativePathname()
                );
                $commandName = basename($classPath, '.php');
                $customCommands[] = new $commandName();
            }

            $this->addCommands($customCommands);
        }
    }
}
