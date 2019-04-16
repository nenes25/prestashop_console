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

/**
 * Class ModuleCommand
 * This command will create a new module file
 * @todo : Validate each options + manage errors + security
 * @package Hhennes\PrestashopConsole\Command\Module\Generate
 */
class ModuleCommand extends Command
{

    /** @var string Module Name */
    protected $_moduleName;

    /** @var Filesystem */
    protected $_fileSystem;

    protected function configure()
    {
        $this
            ->setName('module:generate:module')
            ->setDescription('Generate module default file')
            ->addArgument(
                'name', InputArgument::REQUIRED, 'module name'
            )
            ->addOption('interactive', 'i', InputOption::VALUE_OPTIONAL, 'Interactive Mode')
            ->addOption('author', 'a', InputOption::VALUE_OPTIONAL, 'Module author name', 'hhennes')
            ->addOption('displayName', 'dn', InputOption::VALUE_OPTIONAL, 'Display Name', 'Your module display name')
            ->addOption('description', 'd', InputOption::VALUE_OPTIONAL, 'Description', 'Your module description')
            ->addOption('hookList', 'l', InputOption::VALUE_OPTIONAL, 'Comma separated hook List')
            ->addOption('widget', 'w', InputOption::VALUE_OPTIONAL, 'Implement widget interface')
            ->addOption('templates','t',InputOption::VALUE_OPTIONAL, 'Generate hook templates');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument('name');
        $this->_moduleName = $moduleName;
        $this->_fileSystem = new Filesystem();

        if (is_dir(_PS_MODULE_DIR_ . $moduleName)) {
            $output->writeln('<error>Module already exists</error>');
        } else {
            try {
                $this->_fileSystem->mkdir(_PS_MODULE_DIR_ . $moduleName, 0775);
            }catch (IOException $e) {
                $output->writeln('<error>Unable to creat controller directories</error>');
                return false;
            }
        }

        //Interactive Option : We ask for each cases
        if ($input->getOption('interactive')) {
            $helper = $this->getHelper('question');
            $author = $helper->ask($input, $output, new Question('<question>Module author :</question>'));
            $displayName = $helper->ask($input, $output, new Question('<question>Module Display name :</question>'));
            $description = $helper->ask($input, $output, new Question('<question>Module description :</question>'));
            $hookList = $helper->ask($input, $output, new Question('<question>Module hook List :</question>'));

            $widgetAnswer = $helper->ask($input, $output,
                new ChoiceQuestion('<question>Implement widget Interface :</question>',
                    array(
                        'No',
                        'Yes',
                    )
                )
            );
            $widgetAnswer == "No" ? $widget = null : $widget = true;

            if ( $hookList ) {
                $templateAnswer = $helper->ask($input, $output,
                    new ChoiceQuestion('<question>Generate templates for content hooks :</question>',
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

        } else {
            $author = $input->getOption('author');
            $displayName = $input->getOption('displayName');
            $description = $input->getOption('description');
            $hookList = $input->getOption('hookList');
            $widget = $input->getOption('widget');
            $templates = $input->getOption('templates');
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
            $defaultContent);

        //Widget Management
        if ($widget) {
            $defaultContent = $this->_replaceWidgetContent($defaultContent);

        } else {
            $defaultContent = str_replace(array('{useWidget}', '{widgetImplement}', '{widgetFuctions}'), '', $defaultContent);
        }

        if ($hookList) {
            $defaultContent = $this->_replaceHookContent($defaultContent, $hookList,$templates);
        } else {
            $defaultContent = str_replace(array('{registerHooks}', '{hookfunctions}'), '', $defaultContent);
        }

        $this->_fileSystem->dumpFile(_PS_MODULE_DIR_ . $moduleName . '/' . $moduleName . '.php', $defaultContent);
        $output->writeln('<info>Module generated with success</info>');
    }

    /**
     * Default module content file
     * @todo Format content
     * @return string
     */
    protected function _getDefaultContent()
    {
        return
'<?php
'.ModuleHeader::getHeader().'
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
        return str_replace(array(
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
            )
            , $defaultContent);

    }

    /**
     * Add Hook Contents
     * @param mixed $defaultContent
     * @param mixed $hookList comma separated list of module hooks
     * @param $generateTemplates bool
     * @return mixed
     */
    protected function _replaceHookContent($defaultContent, $hookList,$generateTemplates)
    {
        $hooks = explode(',', $hookList);
        if (sizeof($hooks)) {
            $registerHook = '|| !$this->registerHook([\'' . implode("','", $hooks) . "'])";
            $hookFunctions = '';
            foreach ($hooks as $hook) {

                if ( $generateTemplates && preg_match('#^display#',$hook)) {
                    $hookContent = 'return $this->display(__FILE__,"views/templates/hook/'.$hook.'.tpl");';
                    $this->_generateTemplate($hook);
                } else {
                    $hookContent = '//@Todo implements function';
                }


$hookFunctions .= '
/**
 * Function '.$hook.'description 
 * @param array $params
 * @return mixed
 */
public function hook' . ucfirst($hook) . '($params){
    '.$hookContent.'
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
     * Generate displayHooks Templates
     * @param $hookName
     */
    protected function _generateTemplate($hookName)
    {

        $this->_createDirectories();

        $defaultContent = '<p>Content of hook '.$hookName.' generated by Prestashop Console</p>';
        $fileName = _PS_MODULE_DIR_ . $this->_moduleName. '/views/templates/hook/'.$hookName.'.tpl';
        $this->_fileSystem->dumpFile($fileName,$defaultContent);
    }

    /**
     * Create module controllers directories
     * @Todo : generate index.php files
     * @param $moduleName
     */
    protected function _createDirectories()
    {
        if (!$this->_fileSystem->exists(_PS_MODULE_DIR_ . $this->_moduleName . '/views/templates/hook') ){
            $this->_fileSystem->mkdir(_PS_MODULE_DIR_ . $this->_moduleName . '/views/templates/hook', 0775);
        }
    }
}