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

namespace Hhennes\PrestashopConsole\Command\Module;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet d'activer un module
 *
 */
class DisableCommand extends Command
{
    protected function configure()
    {
        $this
                ->setName('module:disable')
                ->setDescription('Disable module')
                ->addArgument(
                        'name', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'module name ( separate multiple with spaces )'
                );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $name = $input->getArgument('name');

        if (count($name) > 0) {

            foreach ($name as $moduleName) {

                if ($module = \Module::getInstanceByName($moduleName)) {

                    if (\Module::isInstalled($module->name)) {
                        try {
                            $module->disable();
                        } catch (PrestashopException $e) {
                            $outputString = '<error>Error : module ' . $moduleName . ' ' . $e->getMesage() . "<error>";
                            $output->writeln($outputString);
                            return;
                        }
                        $outputString = '<info>Module ' . $moduleName . ' disable with sucess' . "</info>";
                    } else {
                        $outputString = '<error>Error : module ' . $moduleName . ' is not installed' . "<error>";
                    }
                } else {
                    $outputString = '<error>Error : Unknow module name ' . $moduleName . "</error>";
                }
                $output->writeln($outputString);
            }
        }
    }

}
