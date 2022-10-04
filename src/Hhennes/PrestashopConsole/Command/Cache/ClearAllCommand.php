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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PrestaShop\PrestaShop\Adapter\Cache\CacheClearer;
use Tools;

/**
 * Clear all caches commands
 */
class ClearAllCommand extends Command
{
    protected function configure()
    {
        $this
                ->setName('cache:clearAll')
                ->setDescription('Clear all cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $cacheClearer = new CacheClearer();
            $cacheClearer->clearAllCaches();
            $output->writeln("<info>All cache cleared with success</info>");
        } else {
            $output->writeln("<error>This command is only available for Prestashop > 1.7.0.0 </error>");
            return 1;
        }
    }
}
