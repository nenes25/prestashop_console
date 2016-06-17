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
namespace Hhennes\PrestashopConsole\Command\Cache\Smarty;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet de supprimer tout le cache smarty
 * (Fichiers compilés + cache)
 * Pour l'instant nécessite la function exec
 * @todo Optmimiser pour ne pas supprimer le fichier index.php + gérer le mode SQL
 */
class ClearCommand extends Command
{
    protected function configure()
    {
        $this
                ->setName('cache:smarty:clear')
                ->setDescription('Clear smarty cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ( function_exists('exec') ) {

            $smartyCompileDir = _PS_CACHE_DIR_.'smarty/compile';
            $smartyCacheDir = _PS_CACHE_DIR_.'smarty/cache';

            if ( is_dir($smartyCompileDir)) {
                exec("rm -rf $smartyCompileDir/*");
                $output->writeln('<info>Smarty Compile dir cleaned</info>');
            }

            if ( is_dir($smartyCacheDir)) {
                exec("rm -rf $smartyCacheDir/*");
                $output->writeln('<info>Smarty Cache dir cleaned</info>');
            }
        }
        else {
            $output->writeln('<error>Unable to clear smarty cache, exec function is disabled</error>');
        }
    }
}
