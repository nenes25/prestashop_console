<?php
/**
 * 2007-2021 Hennes Hervé
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
 * @copyright 2007-2021 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * https://github.com/nenes25/prestashop_console*
 * https://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole\Command\Parameters\Generate;

use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tools;

/**
 * Class RobotsTxtCommand
 * This command will generate the robots.txt file
 */
class RobotsTxtCommand extends Command
{
    protected function configure(): void
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $input->getOption('executeHook') ? $executeHook = true : $executeHook = false;

        if (true === Tools::generateRobotsFile($executeHook)) {
            $output->writeln('<info>robots.txt file generated with success</info>');
        } else {
            $output->writeln('<error>An error occurs while generating robots.txt file</error>');

            return self::RESPONSE_ERROR;
        }

        return self::RESPONSE_SUCCESS;
    }
}
