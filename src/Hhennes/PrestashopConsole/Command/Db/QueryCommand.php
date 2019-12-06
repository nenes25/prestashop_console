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

namespace Hhennes\PrestashopConsole\Command\Db;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Db;
use PrestaShopDatabaseException;

class QueryCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:query')
            ->addOption('query', 's', InputOption::VALUE_REQUIRED)
            ->setDescription('Run sql query on prestashop db')
            ->setHelp('This command will exec db query using the prestashop Db class, its only allow SELECT queries');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool | void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $query = $input->getOption('query');
        if (null === $query) {
            $output->writeln('<error>No query given</error>');
            return false;
        }

        $query = trim($query);

        //Only allow select queries
        if (preg_match('#^SELECT#i', $query)) {
            try {
                $results = Db::getInstance()->executeS($query);

                if ($results) {
                    $table = new Table($output);
                    $table->setHeaders(array_keys($results[0]));
                    foreach ($results as $result) {
                        $table->addRow(
                            array_values($result)
                        );
                    }
                    $table->render();
                } else {
                    $output->writeln('<info>No results for your query</info>');
                }
            } catch (PrestaShopDatabaseException $e) {
                $output->writeln('<error>' . $e->getMessage() . '</error>');
            }
        } else {
            $output->writeln('<error>Only SELECT query are managed for now</error>');
        }
    }
}
