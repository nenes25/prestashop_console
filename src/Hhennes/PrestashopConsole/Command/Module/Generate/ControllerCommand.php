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

namespace Hhennes\PrestashopConsole\Command\Module\Generate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ControllerCommand
 * This command will create a new module controller
 * @package Hhennes\PrestashopConsole\Command\Module\Generate
 */
class ControllerCommand extends Command
{

    /** @var array Allowed Controllers Types */
    protected $_allowedControllerTypes = array('front', 'admin');

    protected function configure()
    {
        $this
            ->setName('module:generate:controller')
            ->setDescription('Generate module controller file')
            ->addArgument('moduleName', InputArgument::REQUIRED, 'module name')
            ->addArgument('controllerName', InputArgument::REQUIRED, 'controller name')
            ->addArgument('controllerType', InputArgument::REQUIRED, 'controller type')
            ->addOption('full', null, InputArgument::OPTIONAL, 'full mode', true);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument('moduleName');
        $controllerName = $input->getArgument('controllerName');
        $controllerType = $input->getArgument('controllerType');
        $full = $input->getOption('full');

        if (!is_dir(_PS_MODULE_DIR_ . $moduleName)) {
            $output->writeln('<error>Module not exists</error>');
            return false;
        }

        if (!in_array($controllerType, $this->_allowedControllerTypes)) {
            $output->writeln('<error>Unknown controller type</error>');
            return false;
        }

        $this->_createDirectories($moduleName);

        if ($controllerType == 'admin') {
            $defaultContent = $this->_getAdminControllerContent($full);
            $controllerClass = ucfirst($moduleName) . ucfirst($controllerName);
        } else {
            $defaultContent = $this->_getFrontControllerContent($full);
            $controllerClass = ucfirst($controllerName);
        }

        $defaultContent = str_replace('{controllerClass}', $controllerClass, $defaultContent);

        //@ToDO : en full mode déjà remplir un canevas par défaut
        if ($full) {
            $defaultContent = str_replace('{full}', $this->_getFullContent(), $defaultContent);
        } else {
            $defaultContent = str_replace('{full}', '', $defaultContent);
        }

        file_put_contents(
            _PS_MODULE_DIR_ . $moduleName . '/controllers/' . $controllerType . '/' . strtolower($controllerName) . '.php',
            $defaultContent
        );

        echo $output->writeln('<info>Controller ' . $controllerName . ' created with sucess');
    }

    /**
     * Create module controllers directories
     * @Todo : generate index.php files
     * @param $moduleName
     */
    protected function _createDirectories($moduleName)
    {
        if (!is_dir(_PS_MODULE_DIR_ . $moduleName . '/controllers')) {
            mkdir(_PS_MODULE_DIR_ . $moduleName . '/controllers', 0775);
        }

        if (!is_dir(_PS_MODULE_DIR_ . $moduleName . '/controllers/front')) {
            mkdir(_PS_MODULE_DIR_ . $moduleName . '/controllers/front', 0775);
        }

        if (!is_dir(_PS_MODULE_DIR_ . $moduleName . '/controllers/admin')) {
            mkdir(_PS_MODULE_DIR_ . $moduleName . '/controllers/admin', 0775);
        }
    }

    /**
     * Return default adminControllerContent
     * @return string
     */
    protected function _getAdminControllerContent()
    {
        return '
        <?php
        '.ModuleHeader::getHeader().'
        class {controllerClass}Controller extends ModuleAdminController {
         
         {full}
        
        }';
    }

    /**
     * Return default FrontControllerContent
     * @return string
     */
    protected function _getFrontControllerContent()
    {
        return '
        <?php
        '.ModuleHeader::getHeader().'
        class {controllerClass}ModuleFrontController extends ModuleFrontController {
            
            {full}
        
        }';

    }

    /**
     * Basic needed content for controller
     * @todo : 1 for back 1 for front + more functions
     * @return string
     */
    protected function _getFullContent()
    {
        return '
    public function postProcess()
    {
     parent::postProcess();
    }
    
    public function initContent(){
     parent::initContent();
    }
        ';
    }
}