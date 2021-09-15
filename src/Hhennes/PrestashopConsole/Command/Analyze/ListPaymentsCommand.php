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

namespace Hhennes\PrestashopConsole\Command\Analyze;

use PaymentModule;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListPaymentsCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('analyze:payments')
            ->setDescription('List all payments modules on the website');
        //->addOption('format', null, InputOption::VALUE_OPTIONAL, 'outputFormat', null);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modules = PaymentModule::getInstalledPaymentModules();
        if ($modules && count($modules)) {
            $nbModules = 0;
            $table = new Table($output);
            $table->setHeaders(
                [
                    'name',
                ]
            );
            foreach ($modules as $module) {
                $table->addRow(
                    [
                        $module['name'],
                    ]
                );
                $nbModules++;
            }
            $output->writeln('<info>' . $nbModules . ' payments modules on the website</info>');
            $table->render();
        } else {
            $output->writeln('No payments modules installed');
        }

        return 0;
    }
}
