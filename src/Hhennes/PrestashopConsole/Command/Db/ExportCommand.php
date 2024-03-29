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

namespace Hhennes\PrestashopConsole\Command\Db;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Db;

class ExportCommand extends Command
{

    /** @var string[] */
    protected $_allowedTypes = [
        'all',
        'customers',
        'orders',
        'catalog',
    ];

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('db:export')
            ->setDescription('Create db export ')
            ->addOption('type', 't', InputOption::VALUE_OPTIONAL, 'allowed values all|customers|orders|catalog', 'all')
            ->addOption('gzip', 'g', InputOption::VALUE_OPTIONAL, 'compress export in gzip')
            ->addOption('filename', 'f', InputOption::VALUE_OPTIONAL, 'custom file name for export')
            ->addOption('single-transaction', null, InputOption::VALUE_NONE, 'include option --single-transaction to mysqldump')
            ->addOption('no-tablespaces', null, InputOption::VALUE_NONE, 'include option --no-tablespaces to mysqldump')
            ->setHelp('This command will export current prestashop database using mysqldump shell command');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Shell_exec function is required
        if (!function_exists('shell_exec')) {
            $output->writeln('<error>The function shell_exec is not present</error>');
            return 1;
        }

        $type = $input->getOption('type');
        $gzip = $input->getOption('gzip');
        $fileName = $input->getOption('filename');
        $singleTransaction = "";
        $noTableSpaces = "";
        if (null != $input->getOption('single-transaction')) {
            $singleTransaction = '--single-transaction';
        }
        if (null != $input->getOption('no-tablespaces')) {
            $noTableSpaces = '--no-tablespaces';
        }

        if (!in_array($type, $this->_allowedTypes)) {
            $output->writeln('<error>Unknown type option for export</error>');
            return 1;
        }

        $output->writeln('<info>Export started</info>');

        //Manage the case if the server use a custom port
        if (false !== strpos(_DB_SERVER_, ':')) {
            $parts = explode(':', _DB_SERVER_);
            $server = $parts[0]. ' -P '.$parts[1].' ';
        } else {
            $server = _DB_SERVER_;
        }

        $dumpOptions = $singleTransaction.' '.$noTableSpaces;
        $command = "mysqldump ".$dumpOptions." -h " . $server . ' -u ' . _DB_USER_ . ' -p' . _DB_PASSWD_ . ' ' . _DB_NAME_ . ' ';

        //Export type management
        if ($type !== 'all') {
            //Get table concerned by export
            $getfunction = '_get' . ucfirst($type) . 'Tables';
            $tables = $this->$getfunction();
            $tables = array_map(function ($item) {
                return _DB_PREFIX_ . $item;
            }, $tables);
            $command .= implode(" ", $tables);
        }

        //Get export fileName
        if (null !== $fileName) {
            $fileName = $this->_cleanFileName($fileName);
            if (false === $fileName) {
                $output->writeln('<error>Incorrect export filename</error>');
                return 1;
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
    }


    /**
     * Récupération des tables du catalogue
     * @return array
     */
    protected function _getCustomersTables()
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
     * @return array
     */
    protected function _getOrdersTables()
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
     * @return array
     */
    protected function _getCatalogTables()
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
     * @param $fileName
     * @return string|bool
     */
    protected function _cleanFileName($fileName)
    {
        $fileName = trim($fileName);
        $fileName = str_replace(['.sql','.gz'], '', $fileName);
        if (preg_match('/[^a-z_\-0-9]/i', $fileName)) {
            return false;
        }
        return $fileName;
    }
}
