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
 *
 * https://github.com/nenes25/prestashop_console
 * https://www.h-hennes.fr/blog/
 */

namespace Hhennes\PrestashopConsole\Command\Module\Generate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use ReflectionClass;
use ReflectionException;

/**
 * Class ControllerCommand
 * This command will create a new module controller
 * @package Hhennes\PrestashopConsole\Command\Module\Generate
 */
class ControllerCommand extends Command
{
    /** @var string Controller Type Admin */
    const CONTROLLER_TYPE_ADMIN = 'admin';

    /** @var string Controller Type Front */
    const CONTROLLER_TYPE_FRONT = 'front';

    /** @var array Allowed Controllers Types */
    const ALLOWED_CONTROLLER_TYPES = [self::CONTROLLER_TYPE_FRONT, self::CONTROLLER_TYPE_ADMIN];

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

    /** @var OutputInterface */
    protected $_output;


    protected function configure()
    {
        $this
            ->setName('module:generate:controller')
            ->setDescription('Generate module controller file')
            ->setHelp(
                'This command generate controller for the given module '.PHP_EOL
                .PHP_EOL
                .'You can create front controller '.PHP_EOL
                .'module:generate:controller <comment>samplemodule</comment> <comment>controllerName</comment> <info>front</info> : '.PHP_EOL
                . 'By default a smarty template will be automatically created for this controller.'.PHP_EOL
                .PHP_EOL
                .'Or you can create admin controller : '.PHP_EOL
                .'module:generate:controller <comment>samplemodule</comment> <comment>controllerName</comment> <info>admin</info> : '.PHP_EOL
                .'<info>Experimental feature</info>'.PHP_EOL.
                'You can provide an ObjectModel to generate the grid automatically with option --model=<comment>ObjectModelClass</comment>'.PHP_EOL
                .'This class should exists in the directory "class" of your module'
            )
            ->addArgument('moduleName', InputArgument::REQUIRED, 'module name')
            ->addArgument('controllerName', InputArgument::REQUIRED, 'controller name')
            ->addArgument('controllerType', InputArgument::REQUIRED, 'controller type (front|admin)')
            ->addOption('template', 't', InputArgument::OPTIONAL, 'generate template', true)
            ->addOption('model', 'm', InputArgument::OPTIONAL, 'Model for admin controller');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
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
        $this->_output = $output;

        if (!is_dir(_PS_MODULE_DIR_ . $this->_moduleName)) {
            $output->writeln('<error>Module not exists</error>');
            return 1;
        }

        if (!in_array($this->_controllerType, self::ALLOWED_CONTROLLER_TYPES)) {
            $output->writeln(
                sprintf(
                    '<error>Unknown controller type , allowed types are : %s </error>',
                    implode(',', self::ALLOWED_CONTROLLER_TYPES)
                )
            );
            return 1;
        }

        //Create all module directories
        try {
            $this->_createDirectories();
        } catch (IOException $e) {
            $output->writeln('<error>Unable to create controller directories</error>');
            return 1;
        }
        $controllerClass = ucfirst($this->_moduleName) . ucfirst($this->_controllerName);
        if ($this->_controllerType == 'admin') {
            $controllerClass = $this->_controllerName;
            $defaultContent = $this->_getAdminControllerContent();
            if ($this->_template === true) {
                $output->writeln('<comment>Template cannot be generated for admin controllers</comment>');
            }
            if ($this->_model !== null) {
                $modelContent = $this->_getAdminModelContent();
                $include = 'include_once _PS_MODULE_DIR_."' . $this->_moduleName . '/classes/' . $this->_model . '.php";';
            } else {
                $modelContent = '';
                $include = '';
            }
            $defaultContent = str_replace(['{modelContent}', '{include}'], [$modelContent, $include], $defaultContent);
        } else {
            $controllerClass = ucfirst($this->_moduleName) . ucfirst($this->_controllerName);
            $defaultContent = $this->_getFrontControllerContent();

            if ($this->_template === true) {
                $this->_generateTemplate();
            }
        }


        try {
            $controllerFile = _PS_MODULE_DIR_ . $this->_moduleName . '/controllers/' . $this->_controllerType . '/' . strtolower($this->_controllerName) . '.php';
            $defaultContent = str_replace('{controllerClass}', $controllerClass, $defaultContent);
            $this->_fileSystem->dumpFile(
                $controllerFile,
                $defaultContent
            );
            $output->writeln('<comment>Create or update controller file ' . $controllerFile . '</comment>');
        } catch (IOException $e) {
            $output->writeln('<error>Unable to create controller file</error>');
            return 1;
        }

        $output->writeln('<info>Controller ' . $this->_controllerName . ' created with success</info>');

        return 0;
    }


    /**
     * Generate controller directories
     * @throws \Exception
     * @todo Add add index.php security files
     */
    protected function _createDirectories()
    {
        $adminControllerDir = _PS_MODULE_DIR_ . $this->_moduleName . '/controllers/admin';
        $frontControllerDir = _PS_MODULE_DIR_ . $this->_moduleName . '/controllers/front';
        $frontControllerTemplateDir = _PS_MODULE_DIR_ . $this->_moduleName . '/views/templates/front';
        $needIndexFiles = false;

        if (
            $this->_controllerType == self::CONTROLLER_TYPE_ADMIN
            && $this->_fileSystem->exists($adminControllerDir)
        ) {
            $this->_fileSystem->mkdir($adminControllerDir, 0775);
            $this->_output->writeln('<comment>Create directory : ' . $adminControllerDir . '</comment>');
            $needIndexFiles = true;
        }

        if ($this->_controllerType == self::CONTROLLER_TYPE_FRONT) {
            if (!$this->_fileSystem->exists($frontControllerDir)) {
                $this->_fileSystem->mkdir($frontControllerDir, 0775);
                $this->_output->writeln('<comment>Create directory : ' . $frontControllerDir . '</comment>');
            }

            if (!$this->_fileSystem->exists($frontControllerTemplateDir)) {
                $this->_fileSystem->mkdir($frontControllerTemplateDir, 0775);
                $this->_output->writeln('<comment>Create directory : ' . $frontControllerTemplateDir . '</comment>');
                $needIndexFiles = true;
            }
        }

        if (true === $needIndexFiles) {
            try {
                $this->_addIndexFiles();
            } catch (\Exception $e) {
                $this->_output->writeln('<warning>Unable to run command dev:add-index-files to automatically add index files</warning>');
            }
        }
    }

    /**
     * Add missing index.php files in the module content
     * @throws \Symfony\Component\Console\Exception\ExceptionInterface
     */
    protected function _addIndexFiles()
    {
        $indexCommand = $this->getApplication()->find('dev:add-index-files');
        $arguments = [
            'command' => 'dev:add-index-files',
            'dir' => 'modules/' . $this->_moduleName,
        ];
        $content = $indexCommand->run(new ArrayInput($arguments), $this->_output);
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
            \'title\' => \'' . $this->_controllerName . '\',
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
        $templateFile = _PS_MODULE_DIR_ . $this->_moduleName . '/views/templates/front/' . $this->_controllerName . '.tpl';
        $this->_fileSystem->dumpFile($templateFile, $defaultTemplateContent);
        $this->_output->writeln('<comment>Create or update template file ' . $templateFile . '</comment>');
    }

    /**
     * Get Model Admin Controller Model specific content
     */
    protected function _getAdminModelContent()
    {
        if (is_file(_PS_MODULE_DIR_ . $this->_moduleName . '/classes/' . $this->_model . '.php')) {
            include_once _PS_MODULE_DIR_ . $this->_moduleName . '/classes/' . $this->_model . '.php';

            $reflexionClass = new ReflectionClass($this->_model);
            try {
                $definition = $reflexionClass->getStaticPropertyValue('definition');
                $fields = $definition['fields'];
            } catch (ReflectionException $e) {
                return '';
            }
        } else {
            return '';
        }

        $content = '
        public function __construct(){
        
            $this->bootstrap  = true;
            $this->table      = ' . $this->_model . '::$definition[\'table\'];
            $this->identifier = ' . $this->_model . '::$definition[\'primary\'];
            $this->className  = "' . $this->_model . '::class";
            $this->lang       = true; //@Todo Gerer ce param
            $this->context = Context::getContext();
            $this->addRowAction(\'edit\');
            $this->addRowAction(\'delete\');
        
            $this->fields_list = [';

        foreach ($fields as $key => $params) {
            (isset($params['lang']) && $params['lang'] === true) ? $lang = 'true' : $lang = 'false';
            $content .= ' "' . $key . '"=> [
                "title" => $this->l("' . $key . '"),
                "lang" => ' . $lang . ',
                ],
                ';
        }

        $content .= '];
        
         parent::__construct();
        }
        
        /**
         * Display Object Form
         * @return string
         */
        public function renderForm(){
        
            $this->fields_form = [
             "legend" => [
                 "title" => $this->l("Edit ' . $this->_model . '"),
                 "icon" => "icon-cog"
             ],
             //@Todo : Automaticaly detect types
             "input" => [';
        foreach ($fields as $key => $params) {
            (isset($params['lang']) && $params['lang'] === true) ? $lang = 'true' : $lang = 'false';
            (isset($params['required']) && $params['required'] === true) ? $required = 'true' : $required = 'false';
            $content .= '
               [
                    "type" => "text",
                    "label" => $this->l("' . $key . '"),
                    "name" => "' . $key . '",
                    "lang" => ' . $lang . ',
                    "required" => ' . $required . ',
                ],
                ';
        }
        $content .= '    ],
             "submit" => [
                "title" => $this->l("Save"),
             ]
            ];
        
            return parent::renderForm(); 
        
        }
        
        /**
         * Add button in Toolbar
         * @return void
         */
        public function initPageHeaderToolbar()
        {
            $this->page_header_toolbar_btn[\'new_object\'] = array(
                \'href\' => self::$currentIndex.\'&add' . strtolower($this->_model) . '&token=\'.$this->token,
                \'desc\' => $this->l(\'Add new object\'),
                \'icon\' => \'process-icon-new\'
            );
            parent::initPageHeaderToolbar();
        }
        
        /**
         * Translation Override
         * @param string $string
         * @param string $class
         * @param bool $addslashes
         * @param bool $htmlentities
         * @return string
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
