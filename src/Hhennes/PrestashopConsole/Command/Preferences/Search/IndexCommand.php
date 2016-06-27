<?php

/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    Mariusz Mielnik <mariusz@ecbox.pl>
 * @copyright 2013-2016 Mariusz Mielnik
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  http://www.ecbox.pl
 */
namespace Hhennes\PrestashopConsole\Command\Preferences\Search;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commands: Add missing products to the index or re-build the entire index
 *
 */
class IndexCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('preferences:search:index')
            ->setDescription('Add missing products to the index or re-build the entire index (default)')
            ->addArgument(
                'type', InputArgument::OPTIONAL, 'add|rebuild(default)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $type = $input->getArgument('type');

        \Context::getContext()->shop->setContext(\Shop::CONTEXT_ALL);

        switch ($type) {
            case 'add':
                $output->writeln('<comment>Adding missing products to the index...</comment>');
                \Search::indexation();
                break;
            case 'rebuild':
            default:
                $output->writeln('<comment>Re-building the entire index...</comment>');
                \Search::indexation(1);
                break;
        }

        list($total, $indexed) = \Db::getInstance()->getRow('SELECT COUNT(*) as "0", SUM(product_shop.indexed) as "1" FROM ' . _DB_PREFIX_ . 'product p ' . \Shop::addSqlAssociation('product', 'p') . ' WHERE product_shop.`visibility` IN ("both", "search") AND product_shop.`active` = 1');

        $output->writeln('<info>Currently indexed products: ' . (int)$indexed . ' / ' . (int)$total . '</info>');
    }

}
