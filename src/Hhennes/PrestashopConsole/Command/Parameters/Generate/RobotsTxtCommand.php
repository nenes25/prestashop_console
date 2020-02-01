<?php

namespace Hhennes\PrestashopConsole\Command\Parameters\Generate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tools;

/**
 * Class RobotsTxtCommand
 * This command will generate the robots.txt file
 * @package Hhennes\PrestashopConsole\Command\Parameters\Generate
 */
class RobotsTxtCommand extends Command
{

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('parameters:generate:robots')
            ->setDescription('Generate the robots.txt file')
            ->addOption(
                'executeHook',
                'e',
                InputOption::VALUE_OPTIONAL,
                'Generate actionAdminMetaBeforeWriteRobotsFile hook ?'
            );
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $input->getOption('executeHook') ? $executeHook = true : $executeHook = false;

        if (true === Tools::generateRobotsFile($executeHook)) {
            $output->writeln("<info>robots.txt file generated with success</info>");
        } else {
            $output->writeln("<error>An error occurs while generating robots.txt file</error>");
            return 1;
        }
    }
}
