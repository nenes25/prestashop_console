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
 * @author    Mariusz Mielnik <mariusz@ecbox.pl>
 * @copyright 2013-2016 Mariusz Mielnik
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  http://www.ecbox.pl
 */

namespace Hhennes\PrestashopConsole\Command\Module;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResetCommand extends Command
{

    protected function configure()
    {
        $this->setName('module:reset')
                ->setDescription('Reset module: hard = remove data and reinstall, soft(default) = keep data and reinstall')
                ->addArgument(
                        'name', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'module name ( separate multiple with spaces )'
                )
                ->addOption('type', null, InputOption::VALUE_OPTIONAL, 'hard|soft(default)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $name = $input->getArgument('name');
        $type = $input->getOption('type');

        if (count($name) > 0) {

            foreach ($name as $moduleName) {

                if ($module = \Module::getInstanceByName($moduleName)) {
                    if (\Module::isInstalled($module->name)) {
                        try {
                            $error = false;
                            switch ($type) {
                                case 'hard':
                                    if ($module->uninstall()) {
                                        if (!$module->install()) {
                                            $output->writeln("<error>Cannot install module: '$moduleName'</error>");
                                            $error = true;
                                        }
                                    } else {
                                        $output->writeln("<error>Cannot uninstall module: '$moduleName'</error>");
                                        $error = true;
                                    }
                                    break;
                                case 'soft':
                                default:
                                    if (method_exists($module, 'reset')) {
                                        if (!$module->reset()) {
                                            $output->writeln("<error>Cannot reset module: '$moduleName'</error>");
                                            $error = true;
                                        }
                                    } else {
                                        $output->writeln("<error>Module '$moduleName' doesnt support soft reset</error>");
                                        $error = true;
                                    }
                                    break;
                            }
                        } catch (\PrestashopException $e) {
                            $output->writeln("<error>Module: '$moduleName' $e->getMesage()</error>");
                           $error = true;
                        }
                        if ( !$error ) {
                            $output->writeln("<info>Module '$moduleName' reset with success</info>");
                        }
                    } else {
                        $output->writeln("<comment>Module '$moduleName' is uninstalled</comment>");
                    }
                } else {
                    $output->writeln("<error>Unknow module name '$moduleName' </error>");
                }
            }
        }
    }
}
