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
namespace Hhennes\PrestashopConsole\Command\Module\Hook; //REnseigner Namespace

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet de supprimer les modules d'un hook
 *
 */
class RemoveModuleHooksCommand extends Command
{
     protected function configure()
    {
        $this
                ->setName('module:hook:remove')
                ->setDescription('Remove module to one or several hooks')
                ->addArgument(
                        'name', InputArgument::REQUIRED, 'module name'
                        )
                ->addArgument(
                        'hooks', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'hooks name ( separate multiple with spaces )'
        );
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument('name');
        $hooks = $input->getArgument('hooks');

        if ($module = \Module::getInstanceByName($moduleName)) {

            if (sizeof($hooks)) {
                foreach ( $hooks as $hook) {
                    if (!$module->unregisterHook($hook)) {
                        $output->writeln('<error>Error during hook remove from hook '.$hook.'</error>');
                    } else {
                        $output->writeln('<info>Module remove from hook '.$hook.' with success</info>');
                    }
                }
            } else {
                $output->writeln('<error>Not hooks given for ' . $moduleName . '</error>');
            }

        } else {
            $output->writeln('<error>Error the module ' . $moduleName . ' doesn\'t exists</error>');
        }
    }
}
