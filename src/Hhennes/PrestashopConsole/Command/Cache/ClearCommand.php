<?php

namespace Hhennes\PrestashopConsole\Command\Cache;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet d'activer un module
 *
 * @author hhennes <contact@h-hennes.fr>
 *
 * Status : In progress
 */
class ClearCommand extends Command
{

    protected function configure()
    {
        $this
                ->setName('cache:clear')
                ->setDescription('Clear cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        \Cache::clean('*');

        $output->writeln('Cache cleaned');
    }

}
