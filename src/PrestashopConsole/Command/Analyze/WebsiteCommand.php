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
 * http://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole\Command\Analyze;

use DateTime;
use Db;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebsiteCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('analyze:website')
            ->setDescription('Get website statistics')
            ->setHelp(
                'This command show useful statistics about the website' . PHP_EOL
                . '- Prestashop Version ' . PHP_EOL
                . '- Installation date ' . PHP_EOL
                . '- Customer and orders count and average since installation ' . PHP_EOL
                . '- Products and category count ' . PHP_EOL
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $daysFromCreation = $this->getNbDaysFromCreation(_PS_CREATION_DATE_);
        $nbCustomers = $this->getCustomersCount();
        $nbOrders = $this->getOrdersCount();
        $nbProducts = $this->getCatalogProductsCount();
        $nbCategories = $this->getCatalogCategoryCount();
        $multipleShop = $this->hasMultipleShops();
        $output->writeln('<info>Prestashop version: ' . _PS_VERSION_ . '</info>');
        if (true === $multipleShop) {
            $output->writeln('<info>This website has multiple shops</info>');
        }
        $output->writeln('<info>Used theme name: ' . _THEME_NAME_ . '</info>');
        $output->writeln('<info>This website is installed since: ' . _PS_CREATION_DATE_ . ' (' . $daysFromCreation . ' days)</info>');
        $output->writeln('<info>Number of customers: ' . $nbCustomers . ' (' . round($nbCustomers / $daysFromCreation, 2) . '/day)</info>');
        $output->writeln('<info>Number of orders: ' . $nbOrders . ' (' . round($nbOrders / $daysFromCreation, 2) . '/day)</info>');
        $output->writeln('<info>Number of products: ' . $nbProducts . '</info>');
        $output->writeln('<info>Number of categories: ' . $nbCategories . '</info>');

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Calculate the difference of day from the installation to now
     *
     * @param string $creationDate
     *
     * @return int
     *
     * @throws \Exception
     */
    protected function getNbDaysFromCreation($creationDate)
    {
        $now = new DateTime();

        return $now->diff(new DateTime($creationDate))->days;
    }

    /**
     * Get number of customers
     *
     * @return false|string|null
     */
    protected function getCustomersCount()
    {
        return $this->getCount('customer', 'deleted=0');
    }

    /**
     * Get number of orders
     *
     * @return false|string|null
     */
    protected function getOrdersCount()
    {
        return $this->getCount('orders');
    }

    /**
     * Get number of products
     *
     * @return false|string|null
     */
    protected function getCatalogProductsCount()
    {
        return $this->getCount('product');
    }

    /**
     * Get number of categories
     *
     * @return false|string|null
     */
    protected function getCatalogCategoryCount()
    {
        return $this->getCount('category');
    }

    /**
     * Check if website use multiples shops
     *
     * @return bool
     */
    protected function hasMultipleShops()
    {
        $nbShops = $this->getCount('shop');
        if ($nbShops > 1) {
            return true;
        }

        return false;
    }

    /**
     * Get count from a table with or without conditions
     *
     * @param string $table
     * @param string|void $where
     *
     * @return false|string|null
     */
    protected function getCount($table, $where = '')
    {
        $whereCondition = ($where != '') ? ' WHERE ' . $where : '';

        return Db::getInstance()->getValue('SELECT COUNT(*) FROM ' . _DB_PREFIX_ . $table . $whereCondition);
    }
}
