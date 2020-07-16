<?php
/**
 * 2007-2019 Hennes Hervé
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
 * @copyright 2007-2019 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole\Command\Install;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\ListCommand;

/**
 * This commands display information on ps install
 *
 */
class InfoCommand extends ListCommand
{
    protected function configure()
    {
        $this
            ->setName('install:info')
            ->setDescription('prestashop install info')
            ->setDefinition($this->getNativeDefinition());
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $output->writeln("<error>No prestashop installation detected, please install it or place the console in the right place.</error>");
        $output->writeln("<error>Or run install:install to install a new prestashop website.</error>");
        $output->writeln("<error>All console commands will be available once a prestashop installation will be detected</error>");
    }
}
