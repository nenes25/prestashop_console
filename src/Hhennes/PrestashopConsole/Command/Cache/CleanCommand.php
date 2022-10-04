<?php
/**
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
 * @copyright since 2016 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 *
 * https://github.com/nenes25/prestashop_console
 * https://www.h-hennes.fr/blog/
 */

namespace Hhennes\PrestashopConsole\Command\Cache;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cache;

/**
 * Clean cache
 *
 * @author hhennes <contact@h-hennes.fr>
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

        if (!$key || $key == '') {
            $key = "*";
        }

        $cache =  Cache::getInstance();
        $cache->clean($key);

        $output->writeln('<info>Cache cleaned</info>');
    }
}
