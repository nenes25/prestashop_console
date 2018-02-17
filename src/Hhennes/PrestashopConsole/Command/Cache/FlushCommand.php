<?php
/**
 * 2007-2018 Hennes Hervé
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
 * @copyright 2007-2018 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

namespace Hhennes\PrestashopConsole\Command\Cache;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Flush prestashop cache
 *
 * @author hhennes <contact@h-hennes.fr>
 */
class FlushCommand extends Command
{

    protected function configure()
    {
        $this
                ->setName('cache:flush')
                ->setDescription('Flush cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $cache =  \Cache::getInstance();
        $cache->flush();

        //Specific cacheFS
        if (get_class($cache) == 'cacheFs'){
            $cache::deleteCacheDirectory();
            $cache::createCacheDirectories();
        }

        $output->writeln('<info>Cache flushed</info>');
    }

}
