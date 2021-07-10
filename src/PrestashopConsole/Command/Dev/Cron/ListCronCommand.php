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

namespace PrestashopConsole\Command\Dev\Cron;

use Module;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCronCommand extends Command
{
    /** @var string cron Module Name */
    protected $_cronModuleName = 'cronjobs';

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
                ->setName('dev:cron:list')
                ->setDescription('List cron tasks configured with the module cronjobs');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($module = Module::getInstanceByName($this->_cronModuleName)) {
            if (!Module::isInstalled($module->name) || !$module->active) {
                $output->writeln('<error>' . $this->_cronModuleName . ' is not active or installed');

                return self::RESPONSE_ERROR;
            }

            $output->writeln('<info>Configured cron jobs</info>');

            \CronJobsForms::init($module);
            $cronJobs = \CronJobsForms::getTasksListValues();

            $table = new Table($output);
            $table->setHeaders(['id_cronjob', 'description', 'task', 'hour', 'day', 'month', 'week_day', 'last_execution', 'active']);
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
                    $cronJob['active'],
                    ]);
            }
            $table->render();
        } else {
            $output->writeln('<error>' . $this->_cronModuleName . ' is not installed');

            return self::RESPONSE_ERROR;
        }

        return self::RESPONSE_SUCCESS;
    }
}
