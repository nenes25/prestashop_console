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

namespace Hhennes\PrestashopConsole\Command\Analyze;

use Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TablesSizeCommand extends Command
{

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('analyze:tables:size')
            ->setDescription('Analyze the size of the database tables sorted by size')
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Limit to the number of tables');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $input->getOption('limit');
        if (null !== $limit) {
            $limit = intval($limit);
        }
        $tablesInformations = $this->getBiggestTablesSizes($limit);
        if (count($tablesInformations)) {
            null !== $limit ?
                $title = sprintf('%d biggest tables of current database', $limit) :
                $title = 'Tables of current database';

            $output->writeln('<info>'.$title.'</info>');

            $table = new Table($output);
            $table->setHeaders(
                [
                    'table_name',
                    'size',
                ]
            );

            foreach ($tablesInformations as $tablesInformation) {
                $table->addRow(
                    [
                        $tablesInformation['TABLE_NAME'],
                        $tablesInformation['TABLE_SIZE']
                    ]
                );
            }
            $table->render();
        }

        $output->writeln(sprintf('<info>Global db size %s </info>', $this->getGlobalDbSize()));

        return 0;
    }

    /**
     * Get the biggest tables and their sizes
     * @param int|null $limit
     * @return array
     */
    protected function getBiggestTablesSizes($limit = null)
    {
        $tableQuery = "SELECT
                    TABLE_NAME, TABLE_TYPE, TABLE_COMMENT, AUTO_INCREMENT, TABLE_ROWS,
                    CONCAT ( round(((data_length + index_length) / 1024 / 1024), 2),' MB' ) 'TABLE_SIZE'
                    FROM
                    information_schema.TABLES
                    WHERE
                    table_schema = DATABASE()
                    ORDER BY
                        (data_length + index_length) DESC";

        if (null != $limit) {
            $tableQuery .= " LIMIT ".$limit;
        }

        try {
            $sizeInformation = Db::getInstance()->executeS($tableQuery);
            if ($sizeInformation) {
                return $sizeInformation;
            }
        } catch (\PrestaShopDatabaseException $e) {
        }

        return [];
    }

    /**
     * Get the global database size
     * @return string
     */
    protected function getGlobalDbSize()
    {
        $sizeInformations =  Db::getInstance()->getRow(
            "SELECT
                    SUM(table_rows) 'db_rows',
                    CONCAT ( round(((SUM(data_length) + SUM(index_length)) / 1024 / 1024), 2),' MB' ) 'db_size'
                    FROM
                    information_schema.TABLES
                    WHERE
                    table_schema = DATABASE()
                    GROUP BY
                    table_schema"
        );

        if ($sizeInformations) {
            return $sizeInformations['db_size'];
        }
        return  'unknown';
    }
}
