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
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

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
            ->addOption('domainName',null,InputOption::VALUE_OPTIONAL , 'domainName')
            ->addOption('dbname',null,InputOption::VALUE_OPTIONAL ,'dbname')
            ->addOption('dbuser',null,InputOption::VALUE_OPTIONAL ,'dbuser')
            ->addOption('dbpassword',null,InputOption::VALUE_OPTIONAL ,'dbpassword')
            ->addOption('contactEmail',null,InputOption::VALUE_OPTIONAL ,'contactEmail')
            ->addOption('Adminpassword',null,InputOption::VALUE_OPTIONAL ,'Adminpassword');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        /**
         * First step : Select prestashop version
         */
        $psVersion = new ChoiceQuestion('Please select the version to install', array(
            '1.6.1.0',
            '1.6.1.1',
            '1.6.1.2',
            '1.6.1.3',
            '1.6.1.4',
            '1.6.1.5',
            '1.6.1.6',
            '1.6.1.7',
        ));
        $psVersion->setErrorMessage('Option %s is invalid');

        $installVersion = $helper->ask($input,$output,$psVersion);
        $output->writeln('PS version '.$installVersion.' will be installed');


        /**
         * Second Step : Download prestashop archive
         */
        $output->writeln("<info>Start downloading archive</info>");
        $ch = curl_init();
        $source = "http://www.prestashop.com/download/old/prestashop_'.$installVersion.'.zip";
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $data = curl_exec($ch);
        $destination = "prestashop.zip";
        $file = fopen($destination, "w+");
        fputs($file, $data);
        fclose($file);
        $output->writeln("<info>File downloaded</info>");

        /**
         * Third Step extract archive
         * @todo Define path to extract
         */
        $output->writeln("<info>Unziping file</info>");
        $zip = new \ZipArchive();
        if ( $zip->open('prestashop.zip')){
            $zip->extractTo('.');
            $zip->close();
        }
        else {
            $output->writeln("<error>Unable to unzip downloaded archive</error>");
        }
        $output->writeln("<info>File unziped</info>");

        /**
         * Fourth Step : CLI install ( if possible )
         * @todo Factorize questions + deals with options params
         */
        if (function_exists('exec') &&  !strpos(ini_get("disable_functions"), "exec") ){

            $output->writeln("<info>Please give information for CLI install : </info>");

            $domainNameQuestion = new Question("Domain name (default: ' ') "," ");
            $domainName = $helper->ask($input,$output,$domainNameQuestion);

            $dbnameQuestion = new Question("Db name : (default: prestashop_console )" , "prestashop_console");
            $dbname = $helper->ask($input,$output,$dbnameQuestion);

            $dbuserQuestion = new Question("Db user : (default: root )" , "root");
            $dbuser = $helper->ask($input,$output,$dbuserQuestion);

            $dbpasswordQuestion = new Question("Db passord : (default: root )" , "root");
            $dbpassword = $helper->ask($input,$output,$dbpasswordQuestion);

            $contactEmailQuestion = new Question("Admin email : (default: test@example.com )" , "test@example.com");
            $contactEmail = $helper->ask($input,$output,$contactEmailQuestion);

            $adminpassQuestion = new Question("Admin password : (default: test12345678 )" , "test12345678");
            $adminpass = $helper->ask($input,$output,$adminpassQuestion);

            $output->writeln("<info>Starting CLI install</info>");

            $command = "php prestashop/install/index_cli.php --domain=$domainName  --db_name=$dbname --db_user=$dbuser --db_password=$dbpassword --email=$contactEmail --password=$adminpass";
            $command .= " 2>&1 >> install.log";
            exec($command);
        }
        else {
            $output->writeln("<error>Exec function is needed to install prestashop with CLI</error>");
            $output->writeln("<error>Please install it manually</error>");
            exit();
        }

        //If install.log content == '-- Installation successfull! --' it means everything is ok
        $installResult = trim(file_get_contents('install.log'));

        if ( $installResult == '-- Installation successfull! --' ) {
            $output->writeln('<info>Installation successfull');
            unlink('prestashop.zip');
            unlink('install.log');
        }
        else {
            $output->writeln('<error>Errors occurs during installation</error>');
            $output->writeln("<error>".$installResult."</error>");
        }
    }
}
