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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpgradeCommand
 * This command will create a new upgrade file
 * @Todo Validate version
 * @package Hhennes\PrestashopConsole\Command\Module\Generate
 */
class UpgradeCommand extends Command
{

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
        $convertedVersion = str_replace('.', '_', $moduleVersion);

        if (!is_dir(_PS_MODULE_DIR_ . $moduleName)) {
            $output->writeln('<error>Module not exists</error>');
            return false;
        }

        $this->_createDirectories($moduleName);
        $defaultContent = $this->_getDefaultContent();
        $defaultContent = str_replace('{version}', $convertedVersion, $defaultContent);
        file_put_contents(_PS_MODULE_DIR_ . $moduleName . '/upgrade/install' . $moduleVersion . '.php', $defaultContent);
    }

    /**
     * @return string
     */
    protected function _getDefaultContent()
    {
        return '<?php

if (!defined(\'_PS_VERSION_\')) {
    exit;
}

function upgrade_module_{version}($object)
{
    //@Todo generate content
}
';
    }

    /**
     * Create module controllers directories
     * @Todo : generate index.php files
     * @param $moduleName
     */
    protected function _createDirectories($moduleName)
    {
        if (!is_dir(_PS_MODULE_DIR_ . $moduleName . '/upgrade')) {
            mkdir(_PS_MODULE_DIR_ . $moduleName . '/upgrade', 0775);
        }
    }

}