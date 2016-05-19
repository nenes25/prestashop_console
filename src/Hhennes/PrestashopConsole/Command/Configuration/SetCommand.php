<?php
namespace Hhennes\PrestashopConsole\Command\Configuration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet de définir une valeur de configuration
 *
 * @author hhennes <contact@h-hennes.fr>
 */
class SetCommand extends Command
{
     protected function configure()
    {
        $this
                ->setName('configuration:set')
                ->setDescription('set configuration value')
                ->addArgument('name', InputArgument::REQUIRED, 'configuration name')
                ->addArgument('value', InputArgument::REQUIRED, 'configuration name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $value = $input->getArgument('value');
        \Configuration::updateValue($name,$value);
        $output->writeln("Update configuration ".$name." with ".$value);
    }

}