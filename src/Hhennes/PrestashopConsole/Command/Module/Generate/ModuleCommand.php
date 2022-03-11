<?php

namespace Hhennes\PrestashopConsole\Command\Module\Generate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * Class ModuleCommand
 * This command will create a new module file
 * @todo : Validate each options + manage errors + security
 * @package Hhennes\PrestashopConsole\Command\Module\Generate
 */
class ModuleCommand extends Command
{

    /**
     * @var string Module Name
     */
    protected $_moduleName;

    /**
     * @var Filesystem
     */
    protected $_fileSystem;
    /**
     * @var OutputInterface
     */
    protected $_output;

    protected function configure()
    {
        $this
            ->setName('module:generate:module')
            ->setDescription('Generate module main file')
            ->addArgument('name', InputArgument::REQUIRED, 'module name')
            ->addOption('interactive', 'i', InputOption::VALUE_OPTIONAL, 'Interactive Mode')
            ->addOption('author', 'a', InputOption::VALUE_OPTIONAL, 'Module author name', 'hhennes')
            ->addOption('displayName', 'dn', InputOption::VALUE_OPTIONAL, 'Display Name', 'Your module display name')
            ->addOption('description', 'd', InputOption::VALUE_OPTIONAL, 'Description', 'Your module description')
            ->addOption('hookList', 'l', InputOption::VALUE_OPTIONAL, 'Comma separated hook List')
            ->addOption('widget', 'w', InputOption::VALUE_OPTIONAL, 'Implement widget interface')
            ->addOption('templates', 't', InputOption::VALUE_OPTIONAL, 'Generate hook templates')
            ->addOption('with-configuration', null, InputOption::VALUE_NONE, 'Add a configuration sample form');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument('name');
        $this->_moduleName = $moduleName;
        $this->_fileSystem = new Filesystem();
        $this->_output = $output;

        if (is_dir(_PS_MODULE_DIR_ . $moduleName)) {
            $output->writeln('<error>Module already exists</error>');
            return 1;
        } else {
            try {
                $this->_fileSystem->mkdir(_PS_MODULE_DIR_ . $moduleName, 0775);
            } catch (IOException $e) {
                $output->writeln('<error>Unable to creat controller directories</error>');
                return 1;
            }
        }

        //Interactive Option : We ask for each cases
        if ($input->getOption('interactive')) {
            $helper = $this->getHelper('question');
            $author = $helper->ask($input, $output, new Question('<question>Module author :</question>'));
            $displayName = $helper->ask($input, $output, new Question('<question>Module Display name :</question>'));
            $description = $helper->ask($input, $output, new Question('<question>Module description :</question>'));
            $hookList = $helper->ask($input, $output, new Question('<question>Module hook List :</question>'));

            $widgetAnswer = $helper->ask(
                $input,
                $output,
                new ChoiceQuestion(
                    '<question>Implement widget Interface :</question>',
                    array(
                        'No',
                        'Yes',
                    )
                )
            );
            $widgetAnswer == "No" ? $widget = null : $widget = true;

            if ($hookList) {
                $templateAnswer = $helper->ask(
                    $input,
                    $output,
                    new ChoiceQuestion(
                        '<question>Generate templates for content hooks :</question>',
                        array(
                            'No',
                            'Yes',
                        )
                    )
                );
                $templateAnswer == "No" ? $templates = null : $templates = true;
            } else {
                $templates = null;
            }
            $configuration = null;
        } else {
            $author = $input->getOption('author');
            $displayName = $input->getOption('displayName');
            $description = $input->getOption('description');
            $hookList = $input->getOption('hookList');
            $widget = $input->getOption('widget');
            $templates = $input->getOption('templates');
            $configuration = $input->getOption('with-configuration');
        }

        $defaultContent = $this->_getDefaultContent();

        //General Variables
        $defaultContent = str_replace(
            array(
                '{moduleName}',
                '{moduleClassName}',
                '{author}',
                '{moduleDisplayName}',
                '{moduleDescription}',
            ),
            array(
                $moduleName,
                ucfirst($moduleName),
                $author,
                $displayName,
                $description,
            ),
            $defaultContent
        );

        //Widget Management
        if ($widget) {
            $defaultContent = $this->_replaceWidgetContent($defaultContent);
        } else {
            $defaultContent = str_replace(array('{useWidget}', '{widgetImplement}', '{widgetFuctions}'), '', $defaultContent);
        }

        //Hooks management
        if ($hookList) {
            $defaultContent = $this->_replaceHookContent($defaultContent, $hookList, $templates);
        } else {
            $defaultContent = str_replace(array('{registerHooks}', '{hookfunctions}'), '', $defaultContent);
        }

        //Configuration management
        if ($configuration) {
            $defaultContent = $this->_replaceConfigurationContent($defaultContent);
        } else {
            $defaultContent = str_replace(['{configForm}','{configPrefix}'], '', $defaultContent);
        }

        $moduleFile = _PS_MODULE_DIR_ . $moduleName . '/' . $moduleName . '.php';
        $this->_fileSystem->dumpFile($moduleFile, $defaultContent);
        $output->writeln('<comment>Create file ' . $moduleFile . '</comment>');
        $output->writeln('<info>Module generated with success</info>');
        return 0;
    }

    /**
     * Default module content file
     * @return string
     * @todo Format content
     */
    protected function _getDefaultContent()
    {
        return
            '<?php
' . ModuleHeader::getHeader() . '
if (!defined(\'_PS_VERSION_\')) {
    exit;
}

{useWidget}

class {moduleClassName} extends Module {widgetImplement} {

public function __construct()
{
    $this->name = \'{moduleName}\';
    $this->tab = \'others\';
    $this->version = \'0.1.0\';
    $this->author = \'{author}\';
    $this->bootstrap = true;
    parent::__construct();

    $this->displayName = $this->l(\'{moduleDisplayName}\');
    $this->description = $this->l(\'{moduleDescription}\');
    {configPrefix}
}

/**
 * Installation du module
 * @return bool
 */
public function install()
{
    if (!parent::install()
        {registerHooks}

    ) {
        return false;
    }

    return true;
}

{configForm}

{hookfunctions}

{widgetFuctions}

}
';
    }

    /**
     * Add Widget Functions
     * @param $defaultContent
     * @return mixed
     */
    protected function _replaceWidgetContent($defaultContent)
    {
        return str_replace(
            array(
                '{useWidget}',
                '{widgetImplement}',
                '{widgetFuctions}'
            ),
            array(
                'use PrestaShop\PrestaShop\Core\Module\WidgetInterface;',
                'implements WidgetInterface',
                ' public function renderWidget($hookName = null, array $configuration = [])
    {
        //@TODO Implements RenderWidgetMethod
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
     //@TODO Implements getWidgetVariables
    }'
            ),
            $defaultContent
        );
    }

    /**
     * Add Hook Contents
     * @param mixed $defaultContent
     * @param mixed $hookList comma separated list of module hooks
     * @param $generateTemplates bool
     * @return mixed
     */
    protected function _replaceHookContent($defaultContent, $hookList, $generateTemplates)
    {
        $hooks = explode(',', $hookList);
        if (sizeof($hooks)) {
            $registerHook = '|| !$this->registerHook([\'' . implode("','", $hooks) . "'])";
            $hookFunctions = '';
            foreach ($hooks as $hook) {
                if ($generateTemplates && preg_match('#^display#', $hook)) {
                    $hookContent = 'return $this->display(__FILE__,"views/templates/hook/' . $hook . '.tpl");';
                    $this->_generateTemplate($hook);
                } else {
                    $hookContent = '//@Todo implements function';
                }


                $hookFunctions .= '
/**
 * Function ' . $hook . 'description 
 * @param array $params
 * @return mixed
 */
public function hook' . ucfirst($hook) . '($params){
    ' . $hookContent . '
}' . "\n\n";
            }
            $defaultContent = str_replace(
                array('{registerHooks}', '{hookfunctions}'),
                array($registerHook, $hookFunctions),
                $defaultContent
            );
        }
        return $defaultContent;
    }

    /**
     * Add a sample configuration content
     * @param string $defaultContent
     * @return string
     */
    protected function _replaceConfigurationContent($defaultContent)
    {
        return str_replace(
            ['{configForm}','{configPrefix}'],
            [
            '
            /**
     * Configuration du module
     * @return string
     */
    public function getContent()
    {
        $html = \'\';
        $html .= $this->postProcess();
        $html .= $this->renderForm();

        return $html;
    }

    /**
     * Gestion de l\'affichage du formulaire
    * @return string
        */
    public function renderForm(): string
    {
        $fields_form = [
            \'form\' => [
                \'legend\' => [
                    \'title\' => $this->l(\'Module Configuration\'),
                    \'icon\' => \'icon-cogs\',
                ],
                \'input\' => [
                    [
                        \'type\' => \'text\',
                        \'label\' => $this->l(\'Config value 1\'),
                        \'name\' => $this->configPrefix . \'CONFIG_VALUE_1\',
                        \'required\' => true,
                        \'hint\' => $this->l(\'Config value 1 hint\'),
                        \'empty_message\' => $this->l(\'Config value 1 empty message\'),
                    ],
                ],
                \'submit\' => [
                    \'title\' => $this->l(\'Save\'),
                    \'class\' => \'button btn btn-default pull-right\',
                ],
            ],
        ];

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int)Configuration::get(\'PS_LANG_DEFAULT\'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get(\'PS_BO_ALLOW_EMPLOYEE_FORM_LANG\') ?
            Configuration::get(\'PS_BO_ALLOW_EMPLOYEE_FORM_LANG\') : 0;
        $helper->id = $this->name;
        $helper->submit_action = \'SubmitModuleConfiguration\';
        $helper->currentIndex = $this->context->link->getAdminLink(\'AdminModules\', false)
            . \'&configure=\' . $this->name . \'&tab_module=\' . $this->tab . \'&module_name=\' . $this->name;
        $helper->token = Tools::getAdminTokenLite(\'AdminModules\');
        $helper->tpl_vars = [
            \'fields_value\' => $this->getConfigFieldsValues(),
            \'languages\' => $this->context->controller->getLanguages(),
            \'id_language\' => $this->context->language->id,
        ];

        return $helper->generateForm([$fields_form]);

    }

    /**
     * Traitement du formulaire
     * @return string|void
     */
    public function postProcess()
    {
        if (Tools::isSubmit(\'SubmitModuleConfiguration\')) {
            Configuration::updateValue($this->configPrefix . \'CONFIG_VALUE_1\', Tools::getValue($this->configPrefix . \'CONFIG_VALUE_1\'));
            return $this->displayConfirmation($this->l(\'Settings updated\'));
        }

    }

    /**
     * Récupération des valeurs de configuration du formulaire
     * @return array
     */
    public function getConfigFieldsValues(): array
    {
        return [
            $this->configPrefix . \'CONFIG_VALUE_1\' => Tools::getValue($this->configPrefix . \'CONFIG_VALUE_1\', Configuration::get($this->configPrefix . \'CONFIG_VALUE_1\')),
        ];
    }
            ',
                '$this->configPrefix = strtoupper($this->name).\'_\';',
            ],
            $defaultContent
        );
    }

    /**
     * Generate displayHooks Templates
     * @param string $hookName
     */
    protected function _generateTemplate($hookName)
    {
        $this->_createDirectories();

        $defaultContent = '<p>Content of hook ' . $hookName . ' generated by Prestashop Console</p>';
        $fileName = _PS_MODULE_DIR_ . $this->_moduleName . '/views/templates/hook/' . $hookName . '.tpl';
        $this->_fileSystem->dumpFile($fileName, $defaultContent);
        $this->_output->writeln('<comment>Create file ' . $fileName . '</comment>');
    }

    /**
     * Create module controllers directories
     * @Todo : generate index.php files
     */
    protected function _createDirectories()
    {
        $hookDirectory = _PS_MODULE_DIR_ . $this->_moduleName . '/views/templates/hook';
        if (!$this->_fileSystem->exists($hookDirectory)) {
            $this->_fileSystem->mkdir($hookDirectory, 0775);
            $this->_output->writeln('<comment>Create directory ' . $hookDirectory . '</comment>');
        }
    }
}
