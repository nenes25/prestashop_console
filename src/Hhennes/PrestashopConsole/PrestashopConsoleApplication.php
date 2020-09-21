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

namespace Hhennes\PrestashopConsole;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Finder\Finder;

class PrestashopConsoleApplication extends BaseApplication
{
    const APP_NAME = 'prestashopConsole';
    // Execution of the console from a phar archive
    const EXECUTION_MODE_PHAR = "phar";

    /** @var string php|phar Console run mod */
    protected $_runAs = 'php';

    /** @var string Commands directory */
    protected $_commandsDir = 'src/Hhennes/PrestashopConsole/Command';

    /** @var string Phar archive root location */
    protected $_pharArchiveRootLocation = NULL;

    /**
     * Set RunAs Mode
     * @param string $mode
     */
    public function setRunAs($mode)
    {
        $this->_runAs = $mode;
    }

    /**
     * Get RunAs
     * @return string
     */
    public function getRunAs()
    {
        return $this->_runAs;
    }

    /**
     * Initialize the console application for an execution in phar mode.
     * 
     * @param string $archiveLocation : The location of the phar archive currently executed.
     * 
     * @throws Exception 
     */
    public function initializeForPharExecution($archiveLocation)
    {
        // Assert that the given path is a file in the file system.
        if (!file_exists($archiveLocation)) {
            throw new \Exception("The given phar archive location is not a file : ".$archiveLocation);
        }
        // Assert that the location starts with the PHAR prefix
        if (0 !== strpos($archiveLocation, "phar://")) {
            throw new \Exception(
                "The given phar archive location is not a phar archive path (It must start with phar://) : ".$archiveLocation
            );
        }
        $this->_runAs = PrestashopConsoleApplication::EXECUTION_MODE_PHAR;
        $this->_pharArchiveRootLocation = $archiveLocation;
    }

    /**
     * Automatically Detect Registered commands
     */
    public function getDeclaredCommands()
    {
        // The root of the search depends on the run mode
        $dir = ($this->_runAs == PrestashopConsoleApplication::EXECUTION_MODE_PHAR) ? $this->_pharArchiveRootLocation : getcwd();
        // Command directory
        $dir .= DIRECTORY_SEPARATOR.$this->_commandsDir;

        $finder = new Finder();
        $commands = $finder->files()->name('*Command.php')->in($dir);
        $customCommands = array();
        if (sizeof($commands)) {
            foreach ($commands as $command) {
                $classPath = 'Hhennes\\PrestashopConsole\\Command\\' . str_replace(
                    '/',
                    "\\",
                    $command->getRelativePathname()
                );
                $commandName = basename($classPath, '.php');
                $customCommands[] = new $commandName();
            }

            $this->addCommands($customCommands);
        }
    }

    /**
     * Get Phar path
     * @return string
     */
    protected function _getPharPath()
    {
        return $this->_pharArchiveRootLocation.DIRECTORY_SEPARATOR .$this->_commandsDir;
    }
}
