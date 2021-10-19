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

namespace PrestashopConsole\Command\Preferences\Search;

use Context;
use Db;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Search;
use Shop;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commands: Add missing products to the index or re-build the entire index
 */
class IndexCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('preferences:search:index')
            ->setDescription('Add missing products to the index or re-build the entire index (default)')
            ->addArgument(
                'type',
                InputArgument::OPTIONAL,
                'add|rebuild(default)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = $input->getArgument('type');

        Context::getContext()->shop->setContext(Shop::CONTEXT_ALL);

        switch ($type) {
            case 'add':
                $output->writeln('<comment>Adding missing products to the index...</comment>');
                Search::indexation();
                break;
            case 'rebuild':
            default:
                $output->writeln('<comment>Re-building the entire index...</comment>');
                Search::indexation(1);
                break;
        }

        list($total, $indexed) = Db::getInstance()->getRow('SELECT COUNT(*) as "0", SUM(product_shop.indexed) as "1" FROM ' . _DB_PREFIX_ . 'product p ' . Shop::addSqlAssociation('product', 'p') . ' WHERE product_shop.`visibility` IN ("both", "search") AND product_shop.`active` = 1');

        $output->writeln('<info>Currently indexed products: ' . (int) $indexed . ' / ' . (int) $total . '</info>');

        return self::RESPONSE_SUCCESS;
    }
}
