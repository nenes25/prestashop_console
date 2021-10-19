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

namespace PrestashopConsole\Command\Module\Generate;

use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ControllerCommand
 * This command will create a new module controller
 */
class ControllerCommand extends Command
{
    /** @var array Allowed Controllers Types */
    protected $_allowedControllerTypes = ['front', 'admin'];

    /** @var string Module Name */
    protected $_moduleName;

    /** @var string Controller Name */
    protected $_controllerName;

    /** @var string Controller Type */
    protected $_controllerType;

    /** @var bool Generate template or not */
    protected $_template;

    /** @var Filesystem */
    protected $_fileSystem;

    protected function configure(): void
    {
        $this
            ->setName('module:generate:controller')
            ->setDescription('Generate module controller file')
            ->addArgument('moduleName', InputArgument::REQUIRED, 'module name')
            ->addArgument('controllerName', InputArgument::REQUIRED, 'controller name')
            ->addArgument('controllerType', InputArgument::REQUIRED, 'controller type')
            ->addOption('template', 't', InputArgument::OPTIONAL, 'generate template', true);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->_moduleName = $input->getArgument('moduleName');
        $this->_controllerName = $input->getArgument('controllerName');
        $this->_controllerType = $input->getArgument('controllerType');
        $this->_template = $input->getOption('template');
        $this->_fileSystem = new Filesystem();

        if (!is_dir(_PS_MODULE_DIR_ . $this->_moduleName)) {
            $output->writeln('<error>Module not exists</error>');

            return self::RESPONSE_ERROR;
        }

        if (!in_array($this->_controllerType, $this->_allowedControllerTypes)) {
            $output->writeln('<error>Unknown controller type</error>');

            return self::RESPONSE_ERROR;
        }

        //Create all module directories
        try {
            $this->_createDirectories();
        } catch (IOException $e) {
            $output->writeln('<error>Unable to creat controller directories</error>');

            return self::RESPONSE_ERROR;
        }
        $controllerClass = ucfirst($this->_moduleName) . ucfirst($this->_controllerName);
        if ($this->_controllerType == 'admin') {
            $defaultContent = $this->_getAdminControllerContent();
            if ($this->_template === true) {
                $output->writeln('<info>Template cannot be generated for admin controllers</info>');
            }
        } else {
            $defaultContent = $this->_getFrontControllerContent();
            if ($this->_template === true) {
                $this->_generateTemplate();
            }
        }

        $defaultContent = str_replace('{controllerClass}', $controllerClass, $defaultContent);

        try {
            $this->_fileSystem->dumpFile(
                _PS_MODULE_DIR_ . $this->_moduleName . '/controllers/' . $this->_controllerType . '/' . strtolower($this->_controllerName) . '.php',
                $defaultContent
            );
        } catch (IOException $e) {
            $output->writeln('<error>Unable to creat controller directories</error>');

            return self::RESPONSE_ERROR;
        }

        $output->writeln('<info>Controller ' . $this->_controllerName . ' created with sucess');

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Generate controller directories
     *
     * @throws \Exception
     *
     * @todo Add add index.php security files
     *
     * @return void
     */
    protected function _createDirectories(): void
    {
        if (!$this->_fileSystem->exists(_PS_MODULE_DIR_ . $this->_moduleName . '/controllers/admin')) {
            $this->_fileSystem->mkdir(_PS_MODULE_DIR_ . $this->_moduleName . '/controllers/admin', 0775);
        }

        if (!$this->_fileSystem->exists(_PS_MODULE_DIR_ . $this->_moduleName . '/controllers/front')) {
            $this->_fileSystem->mkdir(_PS_MODULE_DIR_ . $this->_moduleName . '/controllers/front', 0775);
        }

        if (!$this->_fileSystem->exists(_PS_MODULE_DIR_ . $this->_moduleName . '/views/templates/front')) {
            $this->_fileSystem->mkdir(_PS_MODULE_DIR_ . $this->_moduleName . '/views/templates/front', 0775);
        }

        /*$indexCommand = $this->getApplication()->find('dev:add-index-files');
        $arguments = [
            'command' => 'dev:add-index-files',
            'dir' => _PS_MODULE_DIR_ . $this->_moduleName,
        ];
        $indexCommand->run(new ArrayInput([$arguments]),new NullOutput());*/
    }

    /**
     * Return default adminControllerContent
     *
     * @return string
     */
    protected function _getAdminControllerContent(): string
    {
        return
            '<?php
' . ModuleHeader::getHeader() . '
class {controllerClass}Controller extends ModuleAdminController {
 
 
}';
    }

    /**
     * Return default FrontControllerContent
     *
     * @return string
     */
    protected function _getFrontControllerContent(): string
    {
        $controllerContent =
            '<?php
' . ModuleHeader::getHeader() . '
class {controllerClass}ModuleFrontController extends ModuleFrontController {
    
    public function init()
    {
        // TODO: Change the autogenerated stub
        return parent::init(); 
    }

    public function postProcess()
    {
        // TODO: Change the autogenerated stub
        parent::postProcess(); 
    }

    ';
        if ($this->_template === true) {
            $controllerContent .= '
    public function initContent()
    {
        parent::initContent();
        $this->setTemplate(\'module:' . $this->_moduleName . '/views/templates/front/' . $this->_controllerName . '.tpl\');
    }';
        }

        $controllerContent .= '
}';

        return $controllerContent;
    }

    /**
     * Generate Template for front Controller
     *
     * @return void
     */
    protected function _generateTemplate(): void
    {
        $defaultTemplateContent =
            '{extends file=\'page.tpl\'}
{block name="content"}
<p>Controller template generated by PrestashopConsole to edit</p>
{/block}
';
        $this->_fileSystem->dumpFile(
            _PS_MODULE_DIR_ . $this->_moduleName . '/views/templates/front/' . $this->_controllerName . '.tpl',
            $defaultTemplateContent
        );
    }
}
