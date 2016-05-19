<?php

namespace Hhennes\PrestashopConsole\Command\Module;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet d'activer un module
 *
 * @author hhennes <contact@h-hennes.fr>
 */
class EnableCommand extends Command
{
     protected function configure()
    {
        $this
                ->setName('module:enable')
                ->setDescription('Enable module')
                ->addArgument(
                        'name', InputArgument::OPTIONAL, 'module name'
                );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $name = $input->getArgument('name');

        if ($module = \Module::getInstanceByName($name)) {

            if (\Module::isInstalled($module->name)) {

                // Exécution de l'action du module
                try {
                    $module->enable();
                } catch (PrestashopException $e) {
                    $outputString = 'Error : module ' . $name . ' ' . $e->getMesage() . "\n";
                    $output->writeln($outputString);
                    return;
                }
                $outputString = 'Module '.$name.' enable with sucess'."\n";
            } else {
                $outputString = 'Error : module ' . $name . ' is not installed' . "\n";
            }
        } else {
            $outputString = 'Error : Unknow module name '.$name."\n";
        }
        $output->writeln($outputString);
    }

}
