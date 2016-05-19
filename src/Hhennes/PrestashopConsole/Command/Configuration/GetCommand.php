<?php
namespace Hhennes\PrestashopConsole\Command\Configuration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet de récupérer une valeur de configuration
 *
 * @author hhennes <contact@h-hennes.fr>
 */
class GetCommand extends Command
{
     protected function configure()
    {
        $this
                ->setName('configuration:get')
                ->setDescription('get configuration value')
                ->addArgument(
                        'name', InputArgument::REQUIRED, 'configuration name'
                );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $value = \Configuration::get($name);
        $output->writeln($value);
    }

}