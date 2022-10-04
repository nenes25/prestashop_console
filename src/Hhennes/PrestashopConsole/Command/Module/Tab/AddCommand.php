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

namespace Hhennes\PrestashopConsole\Command\Module\Tab;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Module;
use Tab;
use Language;

/**
 * This command create new admin tab for given module
 */
class AddCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('module:tab:add')
            ->setDescription('Add module admin tab')
            ->addArgument('name', InputArgument::REQUIRED, 'module name')
            ->addArgument('tab', InputArgument::REQUIRED, 'tab class name')
            ->addArgument('label', InputArgument::REQUIRED, 'tab label')
            ->addOption(
                'parentTab',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Parent tab',
                'DEFAULT'
            )
            ->addOption('icon', 'i', InputOption::VALUE_OPTIONAL, 'Tab icon')
            ->setHelp('Allow to add a new admin tab (controller )');
    }


    /**
     * @inheritDoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument('name');
        $tabClass = $input->getArgument('tab');
        $label = $input->getArgument('label');
        $parentTab = $input->getOption('parentTab');
        $icon = $input->getOption('icon');

        if ($module = Module::getInstanceByName($moduleName)) {
            try {
                $tab = new Tab();
                $tab->class_name = $tabClass;
                $tab->module = $moduleName;
                $tab->id_parent = (int)Tab::getIdFromClassName($parentTab);
                if (null !== $icon) {
                    $tab->icon = $icon;
                }
                $languages = Language::getLanguages();
                foreach ($languages as $lang) {
                    $tab->name[$lang['id_lang']] = $label;
                }

                $tab->save();
            } catch (\Exception $e) {
                $output->writeln('<error>' . $e->getMessage() . '</error>');
                return 1;
            }
            $output->writeln('<info>Tab ' . $tabClass . ' added with success');
        } else {
            $output->writeln('<error>Error the module ' . $moduleName . ' doesn\'t exists</error>');
            return 1;
        }
    }
}
