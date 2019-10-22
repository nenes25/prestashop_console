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
use Symfony\Component\Console\Question\Question;
use Db;

class ImportCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('db:import')
            ->setDescription('Import db dump ')
            ->addOption('file', 'f', InputOption::VALUE_REQUIRED)
            ->addOption('gzip', 'g', InputOption::VALUE_OPTIONAL, 'gzip ')
            ->setHelp('This command will import dumb (gziped or not ) in current prestashop database using mysql shell command');
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
            return false;
        }

        $file = $input->getOption('file');
        $gzip = $input->getOption('gzip');

        if (!is_file($file)) {
            $output->writeln('<error>The import file does not exists</error>');
            return false;
        }

        if (null !== $gzip) {
            $command = 'zcat ' . $file . ' | mysql -h ' . _DB_SERVER_ . ' -u ' . _DB_USER_ . ' -p' . _DB_PASSWD_ . ' ' . _DB_NAME_;
        } else {
            $command = 'mysql -h ' . _DB_SERVER_ . ' -u ' . _DB_USER_ . ' -p' . _DB_PASSWD_ . ' ' . _DB_NAME_ . ' < ' . $file;
        }
        $output->writeln('<info>Import started</info>');
        $import = shell_exec($command);
        $output->writeln($import);
        $output->writeln('<info>Import ended</info>');

    }

}