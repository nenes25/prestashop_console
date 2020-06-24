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

namespace Hhennes\PrestashopConsole\Command\Module\Generate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ControllerCommand
 * This command will create a new module controller
 * @package Hhennes\PrestashopConsole\Command\Module\Generate
 */
class ControllerCommand extends Command
{

    /** @var array Allowed Controllers Types */
    protected $_allowedControllerTypes = array('front', 'admin');

    /** @var string Module Name */
    protected $_moduleName;

    /** @var string Controller Name */
    protected $_controllerName;

    /** @var string Controller Type */
    protected $_controllerType;

    /** @var bool Generate template or not */
    protected $_template;

    /** @var null || ObjectModel for admin controller */
    protected $_model;

    /** @var Filesystem */
    protected $_fileSystem;


    protected function configure()
    {
        $this
            ->setName('module:generate:controller')
            ->setDescription('Generate module controller file')
            ->addArgument('moduleName', InputArgument::REQUIRED, 'module name')
            ->addArgument('controllerName', InputArgument::REQUIRED, 'controller name')
            ->addArgument('controllerType', InputArgument::REQUIRED, 'controller type')
            ->addOption('template', 't', InputArgument::OPTIONAL, 'generate template', true)
            ->addOption('model', 'm', InputArgument::OPTIONAL, 'Model for admin controller');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|int|void|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_moduleName = $input->getArgument('moduleName');
        $this->_controllerName = $input->getArgument('controllerName');
        $this->_controllerType = $input->getArgument('controllerType');
        $this->_template = $input->getOption('template');
        $this->_model = $input->getOption('model');
        $this->_fileSystem = new Filesystem();

        if (!is_dir(_PS_MODULE_DIR_ . $this->_moduleName)) {
            $output->writeln('<error>Module not exists</error>');
            return 1;
        }

        if (!in_array($this->_controllerType, $this->_allowedControllerTypes)) {
            $output->writeln('<error>Unknown controller type</error>');
            return 1;
        }

        //Create all module directories
        try {
            $this->_createDirectories();
        } catch (IOException $e) {
            $output->writeln('<error>Unable to creat controller directories</error>');
            return 1;
        }
        $controllerClass = ucfirst($this->_moduleName) . ucfirst($this->_controllerName);
        if ($this->_controllerType == 'admin') {
            $controllerClass = $this->_controllerName;
            $defaultContent = $this->_getAdminControllerContent();
            if ($this->_template === true) {
                $output->writeln('<info>Template cannot be generated for admin controllers</info>');
            }
            if ($this->_model !== null) {
                $modelContent = $this->_getAdminModelContent();
                $include = 'include_once _PS_MODULE_DIR_."'.$this->_moduleName.'/classes/'.$this->_model.'.php";';
            } else {
                $modelContent = '';
                $include = '';
            }
            $defaultContent = str_replace(['{modelContent}','{include}'], [$modelContent,$include], $defaultContent);
        } else {
            $controllerClass = ucfirst($this->_moduleName) . ucfirst($this->_controllerName);
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
            return 1;
        }

        echo $output->writeln('<info>Controller ' . $this->_controllerName . ' created with sucess');
    }


    /**
     * Generate controller directories
     * @throws \Exception
     * @todo Add add index.php security files
     */
    protected function _createDirectories()
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
     * @return string
     */
    protected function _getAdminControllerContent()
    {
        return
            '<?php
' . ModuleHeader::getHeader() . '
{include}
class {controllerClass}Controller extends ModuleAdminController {
 
 {modelContent}
 
}';
    }

    /**
     * Return default FrontControllerContent
     * @return string
     */
    protected function _getFrontControllerContent()
    {
        $controllerContent =
            '<?php
' . ModuleHeader::getHeader() . '
class {controllerClass}ModuleFrontController extends ModuleFrontController {
    
     /**
     * Manage Controller initialisation
     * @throws PrestaShopException
     */
    public function init()
    {
        // TODO: Change the autogenerated stub
        return parent::init(); 
    }

    /**
     * Manage Post Vars
     */
    public function postProcess()
    {
        // TODO: Change the autogenerated stub
        parent::postProcess(); 
    }
    
    /**
     * Generate controller breadCrumb
     * @return array
     */
    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();
        $breadcrumb[\'links\'][] = [
            \'title\' => \''.$this->_controllerName.'\',
            \'url\' => \'#\'
        ];
        return $breadcrumb;
    }

    ';
        if ($this->_template === true) {
            $controllerContent .= '
    /**
     * Controller Content
     * @throws PrestaShopException
     */             
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
     */
    protected function _generateTemplate()
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

    /**
     * Get Model Admin Controller Model specific content
     */
    protected function _getAdminModelContent()
    {
        if ( is_file(_PS_MODULE_DIR_.$this->_moduleName.'/classes/'.$this->_model.'.php')){
            include_once _PS_MODULE_DIR_.$this->_moduleName.'/classes/'.$this->_model.'.php';
        } else {
            return '';
        }

        $content = '
        public function __construct(){
        
            $this->bootstrap  = true;
            $this->table      = "'.$this->_model::$definition['table'].'";
            $this->identifier = "'.$this->_model::$definition['primary'].'";
            $this->className  = "'.$this->_model.'";
            $this->lang       = true; //@Todo Gerer ce param
            $this->context = Context::getContext();
            $this->addRowAction(\'edit\');
            $this->addRowAction(\'delete\');
        
            $this->fields_list = [';

        foreach ( $this->_model::$definition['fields'] as $key => $params){
            ( isset($params['lang']) && $params['lang'] === true) ? $lang = 'true' : $lang = 'false';
            $content .= ' "'.$key.'"=> [
                "title" => $this->l("'.$key.'"),
                "lang" => '.$lang.',
                ],
                ';
        }

        $content .= '];
        
         parent::__construct();
        }
        
        /**
         * Display Object Form
         */
        public function renderForm(){
        
            $this->fields_form = [
             "legend" => [
                 "title" => $this->l("Edit '.$this->_model.'"),
                 "icon" => "icon-cog"
             ],
             //@Todo : Automaticaly detect types
             "input" => [';
        foreach ( $this->_model::$definition['fields'] as $key => $params){
            ( isset($params['lang']) && $params['lang'] === true) ? $lang = 'true' : $lang = 'false';
            ( isset($params['required']) && $params['required'] === true) ? $required = 'true' : $required = 'false';
            $content .= '
               [
                    "type" => "text",
                    "label" => $this->l("'.$key.'"),
                    "name" => "'.$key.'",
                    "lang" => '.$lang.',
                    "required" => '.$required.',
                ],
                ';
        }
        $content .='    ],
             "submit" => [
                "title" => $this->l("Save"),
             ]
            ];
        
            return parent::renderForm(); 
        
        }
        
        /**
         * Add button in Toolbar
         */
        public function initPageHeaderToolbar()
        {
            $this->page_header_toolbar_btn[\'new_object\'] = array(
                \'href\' => self::$currentIndex.\'&add'.strtolower($this->_model).'&token=\'.$this->token,
                \'desc\' => $this->l(\'Add new object\'),
                \'icon\' => \'process-icon-new\'
            );
            parent::initPageHeaderToolbar();
        }
        
        /**
         * Translation Override
         * @param type $string
         * @param type $class
         * @param type $addslashes
         * @param type $htmlentities
         * @return type
         */
        protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
        {
            if ( _PS_VERSION_ >= \'1.7\') {
                return Context::getContext()->getTranslator()->trans($string);
            } else {
                return parent::l($string, $class, $addslashes, $htmlentities);
            }
        }
        ';

        return $content;

    }
}
