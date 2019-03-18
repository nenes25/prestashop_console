<?php

namespace Hhennes\PrestashopConsole\Command\Module\Generate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class ModuleCommand
 * This command will create a new module file
 * @todo : Validate each options + manage errors + security
 * @package Hhennes\PrestashopConsole\Command\Module\Generate
 */
class ModuleCommand extends Command
{

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
            ->addOption('hookList', 'ho', InputOption::VALUE_OPTIONAL, 'Comma separated hook List')
            ->addOption('widget', 'w', InputOption::VALUE_OPTIONAL, 'Implement widget interface');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument('name');

        if (is_dir(_PS_MODULE_DIR_ . $moduleName)) {
            $output->writeln('<error>Module already exists</error>');
        } else {
            mkdir(_PS_MODULE_DIR_ . $moduleName,0775);
        }

        //Interactive Option : We ask for each cases
        if ($input->getOption('interactive')) {
            $helper = $this->getHelper('question');
            $author = $helper->ask($input, $output, new Question('<question>Module author :</question>'));
            $displayName = $helper->ask($input, $output, new Question('<question>Module Display name :</question>'));
            $description = $helper->ask($input, $output, new Question('<question>Module description :</question>'));
            $hookList = $helper->ask($input, $output, new Question('<question>Module hook List :</question>'));
            $widget = $helper->ask($input, $output,
                new ChoiceQuestion('<question>Implement widget Interface :</question>',
                    array(
                        'Yes',
                        'No',
                    )
                )
            );
        } else {
            $author = $input->getOption('author');
            $displayName = $input->getOption('displayName');
            $description = $input->getOption('description');
            $hookList = $input->getOption('hookList');
            $widget = $input->getOption('widget');
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
            $defaultContent = $this->_replaceHookContent($defaultContent, $hookList);
        } else {
            $defaultContent = str_replace(array('{registerHooks}', '{hookfunctions}'), '', $defaultContent);
        }

        file_put_contents(_PS_MODULE_DIR_ . $moduleName . '/' . $moduleName . '.php', $defaultContent);
        $output->writeln('<info>Module generated with success</info>');
    }

    /**
     * Default module content file
     * @todo Format content
     * @return string
     */
    protected function _getDefaultContent()
    {
        return '<?php
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
     * @param $defaultContent
     * @return mixed
     */
    protected function _replaceHookContent($defaultContent, $hookList)
    {
        $hooks = explode(',', $hookList);
        if (sizeof($hooks)) {
            $registerHook = '|| !$this->registerHook([\'' . implode("','", $hooks) . "'])";
            $hookFunctions = '';
            foreach ($hooks as $hook) {
                $hookFunctions .= '
                /**
                 * Function description 
                 * @param array $params
                 */
                public function hook' . ucfirst($hook) . '($params){
                    //@Todo implements function
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
}