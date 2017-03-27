<?php

namespace Hhennes\PrestashopConsole\Command\Cache;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clean cache
 *
 * @author hhennes <contact@h-hennes.fr>
 *
 * Status : In progress
 */
class CleanCommand extends Command
{

    protected function configure()
    {
        $this
                ->setName('cache:clean')
                ->setDescription('Clean cache')
                ->addArgument('key', InputArgument::OPTIONAL, 'key name | default *');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument('key');

        if ( !$key || $key == '') {
            $key = "*";
        }

        $cache =  \Cache::getInstance();
        $cache->clean($key);

        $output->writeln('<info>Cache cleaned</info>');
    }

}
