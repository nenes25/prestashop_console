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
 *  @author    Hennes Hervé <contact@h-hennes.fr>
 *  @copyright 2013-2016 Hennes Hervé
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  http://www.h-hennes.fr/blog/
 */

namespace Hhennes\PrestashopConsole\Command\Dev\Cron;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class ListCronCommand extends Command {

    /** @var string cron Module Name */
    protected $_cronModuleName = 'cronjobs';

    protected function configure() {
        $this
                ->setName('dev:cron:list')
                ->setDescription('List cron tasks configured with the module cronjobs');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        if ($module = \Module::getInstanceByName($this->_cronModuleName)) {

            if (!\Module::isInstalled($module->name) || !$module->active) {
                $output->writeln('<error>' . $this->_cronModuleName . ' is not active or installed');
                return;
            }
            
            $output->writeln('<info>Configured cron jobs</info>');

            \CronJobsForms::init($module);
            $cronJobs = \CronJobsForms::getTasksListValues();

            $table = new Table($output);
            $table->setHeaders(['id_cronjob','description', 'task', 'hour', 'day', 'month', 'week_day', 'last_execution', 'active']);
            foreach ($cronJobs as $cronJob) {
                $table->addRow([
                    $cronJob['id_cronjob'],
                    $cronJob['description'],
                    $cronJob['task'],
                    $cronJob['hour'],
                    $cronJob['day'],
                    $cronJob['month'],
                    $cronJob['week_day'],
                    $cronJob['last_execution'],
                    $cronJob['active']
                    ]);
            }
            $table->render();
        } else {
            $output->writeln('<error>' . $this->_cronModuleName . ' is not installed');
        }
    }

}
