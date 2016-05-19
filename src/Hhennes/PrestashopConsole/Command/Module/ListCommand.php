<?php

namespace Hhennes\PrestashopConsole\Command\Module;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet de récupérer la liste des modules installé
 *
 * @author hhennes <contact@h-hennes.fr>
 */
class ListCommand extends Command
{

    protected function configure()
    {
        $this
                ->setName('module:list')
                ->setDescription('Get installed modules list')
                ->addArgument(
                        'type', InputArgument::OPTIONAL, 'core|others|all(default)'
                )
                ->addOption(
                        'active', null, InputOption::VALUE_NONE, 'List only active module'
                );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $type = $input->getArgument('type');

        /**
         * En fonction du type de module qu'on souhaite afficher
         */
        switch ( $type ) {

            //@ToDO : Pour l'instant Bug avec la récupération XML de la liste des modules dans certains cas
            //Seul la fonction all fonctionne
            case 'core':
                $modules = \Module::getNativeModuleList();
                break;
            case 'others':
                $modules = \Module::getNonNativeModuleList();
                break;
            case 'all':
            default:
                $modules = \Module::getModulesInstalled();
                break;
        }

        /**
         * Filtre des modules uniquement actifs
         * (Prestashop ne renvoie pas les bonnes données avec ses fonctions (...) manque les données shop
         */
        if ($input->getOption('active')) {
            $modules = array_filter($modules,function($row){
                if ( (int)$row['active'] != 1) {
                    unset($row);
                }
                else {
                    return $row;
                }
            });
        }

        $outputString = 'Currently Installed module '.$type."\n";


        foreach ($modules as $module) {
            $outputString .= $module['name'] . ' ' . $module['version'] . "\n";
        }

        $output->writeln($outputString);
    }

}
