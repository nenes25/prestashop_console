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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Configuration;
use Carrier;

class ListCarriersCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('analyze:carriers')
            ->setDescription('List all payments module on the website')
            ->addOption('active', null, InputOption::VALUE_NONE, 'List only active carriers');
        //->addOption('format', null, InputOption::VALUE_OPTIONAL, 'outputFormat', null);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $active = (bool)$input->getOption('active');
        //@todo Manage format when refacto with meta command
        //$format = $input->getOption('format');

        $carriers = Carrier::getCarriers(
            Configuration::get('PS_DEFAULT_LANG'),
            $active
        );
        if ($carriers && count($carriers)) {
            $table = new Table($output);
            $table->setHeaders(
                [
                    'name',
                    'module',
                    'active'
                ]
            );

            $nbCarriers = 0;
            foreach ($carriers as $carrier) {
                $table->addRow(
                    [
                        $carrier['name'],
                        $carrier['is_module'] == 1 ? $carrier['external_module_name'] : 'none',
                        $carrier['active']
                    ]
                );
                $nbCarriers++;
            }
            $output->writeln('<info>'.$nbCarriers.' carriers on the website</info>');
            $table->render();
        } else {
            $output->writeln('<info>No carriers founds</info>');
        }
        return 0;
    }
}
