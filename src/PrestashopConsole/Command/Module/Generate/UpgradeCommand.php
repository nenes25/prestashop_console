<?php
/**
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
 * @copyright since 2016 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * https://github.com/nenes25/prestashop_console
 * https://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole\Command\Module\Generate;

use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class UpgradeCommand
 * This command will create a new upgrade file
 */
class UpgradeCommand extends Command
{
    /** @var string Module Name */
    protected $_moduleName;

    /** @var Filesystem */
    protected $_fileSystem;

    protected function configure(): void
    {
        $this
            ->setName('module:generate:upgrade')
            ->setDescription('Generate module upgrade file')
            ->addArgument('moduleName', InputArgument::REQUIRED, 'module name')
            ->addArgument('moduleVersion', InputArgument::REQUIRED, 'module version');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $moduleName = $input->getArgument('moduleName');
        $moduleVersion = $input->getArgument('moduleVersion');
        $this->_fileSystem = new Filesystem();
        $this->_moduleName = $moduleName;

        if (!$this->_isValidModuleVersion($moduleVersion)) {
            $output->writeln('<error>Module version is not valid</error>');

            return self::RESPONSE_ERROR;
        }
        $convertedVersion = str_replace('.', '_', $moduleVersion);

        if (!is_dir(_PS_MODULE_DIR_ . $moduleName)) {
            $output->writeln('<error>Module not exists</error>');

            return self::RESPONSE_ERROR;
        }

        try {
            $this->_createDirectories();
        } catch (IOException $e) {
            $output->writeln('<error>Unable to creat ugrade directory</error>');

            return self::RESPONSE_ERROR;
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

            return self::RESPONSE_ERROR;
        }

        $output->writeln('<info>Update file generated</info>');

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Check if module version is in correct format
     *
     * @param string $moduleVersion
     *
     * @return bool
     */
    protected function _isValidModuleVersion($moduleVersion)
    {
        return preg_match('#^[0-9]{1}\.[0-9]+\.?[0-9]*$#', $moduleVersion);
    }

    /**
     * @return string
     */
    protected function _getDefaultContent(): string
    {
        return
            '<?php
 ' . ModuleHeader::getHeader() . '

if (!defined(\'_PS_VERSION_\')) {
    exit;
}

/**
 * Update module in version {version}
 * @param Module $module
 */
function upgrade_module_{version}($module)
{
    //@Todo generate content
}
';
    }

    /**
     * Create upgrade directories
     *
     * @todo Add index.php files
     *
     * @return void
     */
    protected function _createDirectories(): void
    {
        if (!$this->_fileSystem->exists(_PS_MODULE_DIR_ . $this->_moduleName . '/upgrade')) {
            $this->_fileSystem->mkdir(_PS_MODULE_DIR_ . $this->_moduleName . '/upgrade', 0775);
        }
    }
}
