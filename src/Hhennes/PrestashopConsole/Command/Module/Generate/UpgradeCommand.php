<?php
/**
 * 2002-2019 ADVISA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is available
 * through the world-wide-web at this URL: http://www.opensource.org/licenses/OSL-3.0
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to mage@advisa.fr so we can send you a copy immediately.
 *
 * @author ADVISA
 * @copyright 2002-2019 ADVISA
 * @license http://www.opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Hhennes\PrestashopConsole\Command\Module\Generate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class UpgradeCommand
 * This command will create a new upgrade file
 * @package Hhennes\PrestashopConsole\Command\Module\Generate
 */
class UpgradeCommand extends Command
{
    /** @var string Module Name */
    protected $_moduleName;

    /** @var Filesystem */
    protected $_fileSystem;

    protected function configure()
    {
        $this
            ->setName('module:generate:upgrade')
            ->setDescription('Generate module upgrade file')
            ->addArgument('moduleName', InputArgument::REQUIRED, 'module name')
            ->addArgument('moduleVersion', InputArgument::REQUIRED, 'module version');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument('moduleName');
        $moduleVersion = $input->getArgument('moduleVersion');
        $this->_fileSystem = new Filesystem();
        $this->_moduleName = $moduleName;

        if (!$this->_isValidModuleVersion($moduleVersion)) {
            $output->writeln('<error>Module version is not valid</error>');
            return 1;
        }
        $convertedVersion = str_replace('.', '_', $moduleVersion);

        if (!is_dir(_PS_MODULE_DIR_ . $moduleName)) {
            $output->writeln('<error>Module not exists</error>');
            return 1;
        }

        try {
            $this->_createDirectories();
        } catch (IOException $e) {
            $output->writeln('<error>Unable to creat ugrade directory</error>');
            return 1;
        }

        $defaultContent = $this->_getDefaultContent();
        $defaultContent = str_replace('{version}', $convertedVersion, $defaultContent);

        try {
            $this->_fileSystem->dumpFile(
                _PS_MODULE_DIR_ . $moduleName . '/upgrade/upgrade-' . $moduleVersion . '.php',
                $defaultContent
            );
        } catch (IOException $e) {
            $output->writeln('<error>Unable to creat upgrade file</error>');
            return 1;
        }

        $output->writeln('<info>Update file generated</info>');
    }

    /**
     * Check if module version is in correct format
     * @param $moduleVersion
     * @return bool
     */
    protected function _isValidModuleVersion($moduleVersion)
    {
        return preg_match('#^[0-9]{1}\.[0-9]+\.?[0-9]*$#', $moduleVersion);
    }

    /**
     * @return string
     */
    protected function _getDefaultContent()
    {
        return
            '<?php
 ' . ModuleHeader::getHeader() . '

if (!defined(\'_PS_VERSION_\')) {
    exit;
}

/**
 * Upgrade module {version} description
 * @param Module $module
 * @return bool
 */
function upgrade_module_{version}($module)
{
    //@Todo generate content
}
';
    }


    /**
     * Create upgrade directories
     * @todo Add index.php files
     */
    protected function _createDirectories()
    {
        if (!$this->_fileSystem->exists(_PS_MODULE_DIR_ . $this->_moduleName . '/upgrade')) {
            $this->_fileSystem->mkdir(_PS_MODULE_DIR_ . $this->_moduleName . '/upgrade', 0775);
        }
    }
}
