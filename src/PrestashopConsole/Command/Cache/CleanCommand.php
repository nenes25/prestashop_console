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

namespace PrestashopConsole\Command\Cache;

use Cache;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clean cache
 *
 * @author hhennes <contact@h-hennes.fr>
 */
class CleanCommand extends Command
{
    protected function configure(): void
    {
        $this
                ->setName('cache:clean')
                ->setDescription('Clean cache')
                ->addArgument('key', InputArgument::OPTIONAL, 'key name | default *');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = $input->getArgument('key');

        if (!$key || $key == '') {
            $key = '*';
        }

        $cache = Cache::getInstance();
        $cache->clean($key);

        $output->writeln('<info>Cache cleaned</info>');

        return self::RESPONSE_SUCCESS;
    }
}
