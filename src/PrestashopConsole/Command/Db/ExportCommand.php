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

namespace PrestashopConsole\Command\Db;

use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCommand extends Command
{
    /** @var string[] */
    protected $_allowedTypes = [
        'all',
        'customers',
        'orders',
        'catalog',
    ];

    protected function configure(): void
    {
        $this
            ->setName('db:export')
            ->setDescription('Create db export ')
            ->addOption('type', 't', InputOption::VALUE_OPTIONAL, 'allowed values all|customers|orders|catalog', 'all')
            ->addOption('gzip', 'g', InputOption::VALUE_OPTIONAL, 'compress export in gzip')
            ->addOption('filename', 'f', InputOption::VALUE_OPTIONAL, 'custom file name for export')
            ->setHelp('This command will export current prestashop database using mysqldump shell command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //Shell_exec function is required
        if (!function_exists('shell_exec')) {
            $output->writeln('<error>The function shell_exec is not present</error>');

            return self::RESPONSE_ERROR;
        }

        $type = $input->getOption('type');
        $gzip = $input->getOption('gzip');
        $fileName = $input->getOption('filename');

        if (!in_array($type, $this->_allowedTypes)) {
            $output->writeln('<error>Unknow type option for export</error>');

            return self::RESPONSE_ERROR;
        }

        $output->writeln('<info>Export started</info>');
        $command = 'mysqldump -h ' . _DB_SERVER_ . ' -u ' . _DB_USER_ . ' -p' . _DB_PASSWD_ . ' ' . _DB_NAME_ . ' ';

        //Export type management
        if ($type !== 'all') {
            //Get table concerned by export
            $getfunction = '_get' . ucfirst($type) . 'Tables';
            $tables = $this->$getfunction();
            $tables = array_map(function ($item) {
                return _DB_PREFIX_ . $item;
            }, $tables);
            $command .= implode(' ', $tables);
        }

        //Get export fileName
        if (null !== $fileName) {
            $fileName = $this->_cleanFileName($fileName);
            if (false === $fileName) {
                $output->writeln('<error>Incorrect export filename</error>');

                return self::RESPONSE_ERROR;
            }
        }
        //Defaut export file name
        if (null === $fileName) {
            $fileName = date('YmdHi') . '-dump' . ($type ? '-' . $type : '');
        }

        ($gzip !== null) ? $command .= ' | gzip ' : '';
        $command .= '> ' . $fileName . '.sql';
        ($gzip !== null) ? $command .= '.gz' : '';
        $export = shell_exec($command);
        $output->writeln('<info>' . $export . '</info>');
        $output->writeln('<info>Export ended</info>');

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Récupération des tables du catalogue
     *
     * @return array
     */
    protected function _getCustomersTables(): array
    {
        return [
            'customer',
            'customer_group',
            'customer_message',
            'customer_message_sync_imap',
            'customer_thread',
            'address',
        ];
    }

    /**
     * Récupération des tables du catalogue
     *
     * @return array
     */
    protected function _getOrdersTables(): array
    {
        return [
            'cart',
            'cart_product',
            'connections',
            'connections_page',
            'connections_source',
            'guest',
            'message',
            'message_readed',
            'orders',
            'order_carrier',
            'order_cart_rule',
            'order_detail',
            'order_detail_tax',
            'order_history',
            'order_invoice',
            'order_invoice_payment',
            'order_invoice_tax',
            'order_message',
            'order_message_lang',
            'order_payment',
            'order_return',
            'order_return_detail',
            'order_slip',
            'order_slip_detail',
            'page',
            'page_type',
            'page_viewed',
            'product_sale',
            'referrer_cache',
        ];
    }

    /**
     * Récupération des tables du catalogue
     *
     * @return array
     */
    protected function _getCatalogTables(): array
    {
        return [
            'product',
            'product_shop',
            'product_lang',
            'category_product',
            'product_tag',
            'tag',
            'image',
            'image_lang',
            'image_shop',
            'product_carrier',
            'cart_product',
            'product_attachment',
            'product_country_tax',
            'product_download',
            'product_group_reduction_cache',
            'product_sale',
            'product_supplier',
            'warehouse_product_location',
            'stock',
            'stock_available',
            'stock_mvt',
            'supply_order_detail',
            'product_attribute',
            'product_attribute_shop',
            'product_attribute_combination',
            'product_attribute_image',
            'attribute_impact',
            'attribute_lang',
            'attribute_group',
            'attribute_group_lang',
            'attribute_group_shop',
            'attribute_shop',
            'manufacturer',
            'manufacturer_lang',
            'manufacturer_shop',
            'supplier',
            'supplier_lang',
            'supplier_shop',
            'customization',
            'customization_field',
            'customization_field_lang',
            'customized_data',
            'feature',
            'feature_lang',
            'feature_product',
            'feature_shop',
            'feature_value',
            'feature_value_lang',
            'pack',
            'search_index',
            'search_word',
            'specific_price',
            'specific_price_priority',
            'specific_price_rule',
            'specific_price_rule_condition',
            'specific_price_rule_condition_group',
            'warehouse',
        ];
    }

    /**
     * Clean fileName to avoid error and Xss injection
     *
     * @param string $fileName
     *
     * @return string|bool
     */
    protected function _cleanFileName($fileName)
    {
        $fileName = trim($fileName);
        $fileName = str_replace(['.sql', '.gz'], '', $fileName);
        if (preg_match('/[^a-z_\-0-9]/i', $fileName)) {
            return false;
        }

        return $fileName;
    }
}
