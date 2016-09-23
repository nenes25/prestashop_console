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
namespace Hhennes\PrestashopConsole\Command\Install;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This commands allow to enable/disable cms categories
 *
 */
class InstallCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('install:install')
            ->setDescription('install prestashop')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Start downloading archive</info>");
        $ch = curl_init();
        $source = "http://www.prestashop.com/download/old/prestashop_1.6.1.7.zip";
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $data = curl_exec($ch);
        $destination = "prestashop.zip";
        $file = fopen($destination, "w+");
        fputs($file, $data);
        fclose($file);
        $output->writeln("<info>File downloaded</info>");

        $output->writeln("<info>Unziping file</info>");
        $output->writeln("<info>To be continued</info>");
    }
}
