<?php
/**
 * 2007-2019 Hennes Hervé
 * 2019-2019 Monterisi Sébastien
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
 * @author    Monterisi Sébastien <contact@seb7.fr>
 * @copyright 2007-2019 Hennes Hervé - 2019-2019 Monterisi Sébastien
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

namespace Hhennes\PrestashopConsole\Command\Module;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Module;

/**
 * Commande qui permet de récupérer la liste des modules installé
 *
 */
class ListCommand extends Command
{
    const AVAILABLE_FILTERS = [
        "active",
        "installed",
        "native",
        "trusted"
    ];

    protected function configure()
    {
        $this
            ->setName('module:list')
            ->setDescription('Get modules list');
        // Add all filters as 2 different options : 
        // One to filter only the modules with the filter attribute (named as the filter)
        // One to filter only the modules with the filter attribute as false (named as the 'no-filter')
        foreach (ListCommand::AVAILABLE_FILTERS as $filter) {
            $this->addOption(
                $filter,
                null,
                InputOption::VALUE_NONE,
                sprintf('List only %s modules', $filter)
            )
            ->addOption(
                'no-' . $filter,
                null,
                InputOption::VALUE_NONE,
                sprintf('List only not %s modules', $filter)
            );
        };
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modules = Module::getModulesOnDisk();
        //module stdClass definition
        /*
            [id] => 36
            [warning] =>
            [name] => gridhtml
            [displayName] => Module display name
            [version] => 1.3.0
            [description] => Module description
            [author] => PrestaShop
            [tab] => administration
            [is_configurable] => 0
            [need_instance] => 0
            [limited_countries] =>
            [author_uri] =>
            [active] => 1
            [onclick_option] =>
            [trusted] => 1
            [installed] => 1
            [database_version] => 1.3.0
            [interest] =>
            [enable_device] => 7
         */

        // Add the filter parameter native with a specific condition (PrestaShop must be in the author of the module)
        // We consider here that this loop will not cost a lot in terms of performance and the code is much cleaner with it.
        foreach ($modules as $module) {
            $module->native = (false !== strpos($module->author, "PrestaShop"));
        }

        // Apply filters for each found option
        foreach (ListCommand::AVAILABLE_FILTERS as $filter) {
            if ($input->getOption($filter)) {
                $modules = array_filter($modules, function ($module) use($filter) {
                    return (bool)$module->{$filter};
                });
            }
            if ($input->getOption("no-" . $filter)) {
                $modules = array_filter($modules, function ($module) use($filter) {
                    return ! (bool)$module->{$filter};
                });
            }
        }
        // sort by module name
        usort($modules, array($this, "cmp"));

        $output->writeln("<info>Currently module on disk:</info>");

        $nr = 0;
        $table = new Table($output);
        $table->setHeaders(['Name', 'Version', 'Installed', 'Active']);
        foreach ($modules as $module) {
            $table->addRow([
                $module->name,
                $module->version,
                ((bool)($module->installed) ? 'true' : 'false'),
                ((bool)($module->active) ? 'true' : 'false')
            ]);
            $nr++;
        }

        $table->render();
        $output->writeln("<info>Total modules on disk: $nr</info>");
    }

    private function cmp($a, $b)
    {
        return strcmp($a->name, $b->name);
    }
}
