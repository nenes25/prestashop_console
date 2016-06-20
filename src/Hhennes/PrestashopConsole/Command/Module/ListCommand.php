<?php

namespace Hhennes\PrestashopConsole\Command\Module;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet de récupérer la liste des modules installé
 *
 */
class ListCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('module:list')
            ->setDescription('Get modules list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $modules = \Module::getModulesOnDisk();
        //TODO sort by name
        /*
         *             [id] => 36
                    [warning] =>
                    [name] => gridhtml
                    [displayName] => Wyświetlanie prostej tabeli HTML
                    [version] => 1.3.0
                    [description] => Pozwól systemowi statystyk na wyświetlanie danych w tabeli.
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

}
