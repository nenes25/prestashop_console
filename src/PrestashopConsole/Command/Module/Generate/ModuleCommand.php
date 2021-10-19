<?php
/**
 * 2007-2021 Hennes Hervé
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
 * @copyright 2007-2021 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * https://github.com/nenes25/prestashop_console*
 * https://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole\Command\Module\Generate;

use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ModuleCommand
 * This command will create a new module file
 *
 * @todo : Validate each options + manage errors + security
 */
class ModuleCommand extends Command
{
    /** @var string Module Name */
    protected $_moduleName;

    /** @var Filesystem */
    protected $_fileSystem;

    protected function configure(): void
    {
        $this
            ->setName('module:generate:module')
            ->setDescription('Generate module default file')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'module name'
            )
            ->addOption('interactive', 'i', InputOption::VALUE_OPTIONAL, 'Interactive Mode')
            ->addOption('author', 'a', InputOption::VALUE_OPTIONAL, 'Module author name', 'hhennes')
            ->addOption('displayName', 'dn', InputOption::VALUE_OPTIONAL, 'Display Name', 'Your module display name')
            ->addOption('description', 'd', InputOption::VALUE_OPTIONAL, 'Description', 'Your module description')
            ->addOption('hookList', 'l', InputOption::VALUE_OPTIONAL, 'Comma separated hook List')
            ->addOption('widget', 'w', InputOption::VALUE_OPTIONAL, 'Implement widget interface')
            ->addOption('templates', 't', InputOption::VALUE_OPTIONAL, 'Generate hook templates');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $moduleName = $input->getArgument('name');
        $this->_moduleName = $moduleName;
        $this->_fileSystem = new Filesystem();

        if (is_dir(_PS_MODULE_DIR_ . $moduleName)) {
            $output->writeln('<error>Module already exists</error>');
        } else {
            try {
                $this->_fileSystem->mkdir(_PS_MODULE_DIR_ . $moduleName, 0775);
            } catch (IOException $e) {
                $output->writeln('<error>Unable to creat controller directories</error>');

                return self::RESPONSE_ERROR;
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
                    [
                        'No',
                        'Yes',
                    ]
                )
            );
            $widgetAnswer == 'No' ? $widget = null : $widget = true;

            if ($hookList) {
                $templateAnswer = $helper->ask(
                    $input,
                    $output,
                    new ChoiceQuestion(
                        '<question>Generate templates for content hooks :</question>',
                        [
                            'No',
                            'Yes',
                        ]
                    )
                );
                $templateAnswer == 'No' ? $templates = null : $templates = true;
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
            [
                '{moduleName}',
                '{moduleClassName}',
                '{author}',
                '{moduleDisplayName}',
                '{moduleDescription}',
            ],
            [
                $moduleName,
                ucfirst($moduleName),
                $author,
                $displayName,
                $description,
            ],
            $defaultContent
        );

        //Widget Management
        if ($widget) {
            $defaultContent = $this->_replaceWidgetContent($defaultContent);
        } else {
            $defaultContent = str_replace(['{useWidget}', '{widgetImplement}', '{widgetFuctions}'], '', $defaultContent);
        }

        if ($hookList) {
            $defaultContent = $this->_replaceHookContent($defaultContent, $hookList, $templates);
        } else {
            $defaultContent = str_replace(['{registerHooks}', '{hookfunctions}'], '', $defaultContent);
        }

        $this->_fileSystem->dumpFile(_PS_MODULE_DIR_ . $moduleName . '/' . $moduleName . '.php', $defaultContent);
        $output->writeln('<info>Module generated with success</info>');

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Default module content file
     *
     * @todo Format content
     *
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
     *
     * @param string $defaultContent
     *
     * @return string
     */
    protected function _replaceWidgetContent($defaultContent): string
    {
        return str_replace(
            [
            '{useWidget}',
            '{widgetImplement}',
            '{widgetFuctions}',
        ],
            [
                'use PrestaShop\PrestaShop\Core\Module\WidgetInterface;',
                'implements WidgetInterface',
                ' public function renderWidget($hookName = null, array $configuration = [])
    {
        //@TODO Implements RenderWidgetMethod
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
     //@TODO Implements getWidgetVariables
    }',
            ],
            $defaultContent
        );
    }

    /**
     * Add Hook Contents
     *
     * @param mixed $defaultContent
     * @param mixed $hookList comma separated list of module hooks
     * @param bool $generateTemplates
     *
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
                ['{registerHooks}', '{hookfunctions}'],
                [$registerHook, $hookFunctions],
                $defaultContent
            );
        }

        return $defaultContent;
    }

    /**
     * Generate displayHooks Templates
     *
     * @param string $hookName
     *
     * @return void
     */
    protected function _generateTemplate($hookName): void
    {
        $this->_createDirectories();

        $defaultContent = '<p>Content of hook ' . $hookName . ' generated by Prestashop Console</p>';
        $fileName = _PS_MODULE_DIR_ . $this->_moduleName . '/views/templates/hook/' . $hookName . '.tpl';
        $this->_fileSystem->dumpFile($fileName, $defaultContent);
    }

    /**
     * Create module controllers directories
     *
     * @Todo : generate index.php files
     *
     * @return void
     */
    protected function _createDirectories(): void
    {
        if (!$this->_fileSystem->exists(_PS_MODULE_DIR_ . $this->_moduleName . '/views/templates/hook')) {
            $this->_fileSystem->mkdir(_PS_MODULE_DIR_ . $this->_moduleName . '/views/templates/hook', 0775);
        }
    }
}
