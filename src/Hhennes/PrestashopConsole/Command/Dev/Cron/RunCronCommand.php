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

class RunCronCommand extends Command {

    /** @var string cron Module Name */
    protected $_cronModuleName = 'cronjobs';

    protected function configure() {
        $this
                ->setName('dev:cron:run')
                ->setDescription('List cron tasks configured with the module cronjobs')
                ->addArgument(
                        'id_cronjob', InputArgument::REQUIRED, 'cron job id ( use command dev:cron:list to get it )'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        if ($module = \Module::getInstanceByName($this->_cronModuleName)) {

            if (!\Module::isInstalled($module->name) || !$module->active) {
                $output->writeln('<error>' . $this->_cronModuleName . ' is not active or installed');
                return;
            }

            $cronjob_id = $input->getArgument('id_cronjob');
            $output->writeln($this->_runTask($cronjob_id));
        } else {
            $output->writeln('<error>' . $this->_cronModuleName . ' is not installed');
        }
    }

    /**
     * Run task
     * @param type $cronjob_id
     * @return string
     */
    protected function _runTask($cronjob_id) {

        $cronJob = \Db::getInstance()->getRow("SELECT id_module,task FROM " . _DB_PREFIX_ . "cronjobs WHERE id_cronjob=" . (int) $cronjob_id);

        if (!$cronJob) {
            return '<error>Unknow cronjob_id</error>';
        }

        if ($cronJob['id_module'] !== NULL) {
            \Hook::exec('actionCronJob', array(), $cronJob['id_module']);
        } else {
            \Tools::file_get_contents(urldecode($cronJob['task']), false);
        }

        \Db::getInstance()->execute("UPDATE " . _DB_PREFIX_ . "cronjobs SET `updated_at` = NOW() WHERE `id_cronjob` =" . (int) $cronjob_id);

        return '<info>Cron job run with success</info>';
    }

}
