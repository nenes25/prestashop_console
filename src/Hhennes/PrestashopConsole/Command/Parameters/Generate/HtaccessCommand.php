<?php

namespace Hhennes\PrestashopConsole\Command\Parameters\Generate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tools;

/**
 * Class HtaccessCommand
 * This command will generate the .htaccess file
 * @package Hhennes\PrestashopConsole\Command\Parameters\Generate
 */
class HtaccessCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('parameters:generate:htaccess')
            ->setDescription('Generate the .htaccess file');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (true === Tools::generateHtaccess()) {
            $output->writeln("<info>.htaccess file generated with success</info>");
        } else {
            $output->writeln("<error>An error occurs while generating .htaccess file</error>");
            return 1;
        }
    }
}
