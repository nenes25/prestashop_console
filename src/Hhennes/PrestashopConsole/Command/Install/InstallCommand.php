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
    /** @var array commands options */
    protected $_options = array(
        'psVersion',
        'domainName',
        'dbname',
        'dbuser',
        'dbpassword',
        'contactEmail',
        'adminpassword',
        'directory'
    );

    protected function configure()
    {
        $this
            ->setName('install:install')
            ->setDescription('install prestashop')
            ->addOption('psVersion',null,InputOption::VALUE_OPTIONAL , 'Prestashop version')
            ->addOption('domainName',null,InputOption::VALUE_OPTIONAL , 'domainName')
            ->addOption('dbname',null,InputOption::VALUE_OPTIONAL ,'dbname')
            ->addOption('dbuser',null,InputOption::VALUE_OPTIONAL ,'dbuser')
            ->addOption('dbpassword',null,InputOption::VALUE_OPTIONAL ,'dbpassword')
            ->addOption('contactEmail',null,InputOption::VALUE_OPTIONAL ,'contactEmail')
            ->addOption('adminpassword',null,InputOption::VALUE_OPTIONAL ,'Adminpassword')
            ->addOption('directory',null,InputOption::VALUE_OPTIONAL ,'Install directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $helper = $this->getHelper('question');

        //Get options values if defined
        foreach( $this->_options as $option){
            ${$option} = $input->getOption($option);
        }

        if ( !$psVersion) {
            /**
             * First step : Select prestashop version
             */
            $psVersion = new ChoiceQuestion('Please select the version to install ( Only compatible with ps 1.6.1 )', array(
                '1.6.1.0',
                '1.6.1.1',
                '1.6.1.2',
                '1.6.1.3',
                '1.6.1.4',
                '1.6.1.5',
                '1.6.1.6',
                '1.6.1.7',
                '1.6.1.8',
                '1.6.1.9',
                '1.6.1.10',
                '1.6.1.11',
                '1.6.1.12',
                '1.6.1.13',
                '1.6.1.14',
                '1.6.1.15',
                '1.6.1.16',
                '1.6.1.17',
                '1.6.1.18',
                '1.6.1.19',
                '1.6.1.20',
                '1.6.1.21',
                '1.6.1.22',
                '1.6.1.23',
                '1.6.1.24',
            ));
            $psVersion->setErrorMessage('Option %s is invalid');

            $installVersion = $helper->ask($input,$output,$psVersion);
            $output->writeln('PS version '.$installVersion.' will be installed');
        }
        else {
            $installVersion = $psVersion;
        }


        /**
         * Second Step : Download prestashop archive
         */
        $output->writeln("<info>Start downloading archive</info>");
        $ch = curl_init();
        $source = "http://www.prestashop.com/download/old/prestashop_".$installVersion.".zip";
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
         * By default in directory "prestashop"
         */
        if (function_exists('exec') &&  !strpos(ini_get("disable_functions"), "exec") ){
            if ( !$directory ) {
                $directoryQuestion = new Question("Install in a subdirectory (default: current directory)",".");
                $directory = $helper->ask($input,$output,$directoryQuestion);
            }
        }
        else {
            $output->writeln("<info>Prestashop will be installed in directory 'prestashop' in current directory");
            $directory = ".";
        }

        $output->writeln("<info>Unziping file</info>");
        $zip = new \ZipArchive();
        if ( $zip->open('prestashop.zip')){
            $zip->extractTo($directory);
            $zip->close();
        }
        else {
            $output->writeln("<error>Unable to unzip downloaded archive</error>");
        }
        $output->writeln("<info>File unziped</info>");

        if ( $directory != "."){
            exec("mv ".$directory."/prestashop/* ".$directory."/");
            exec("rm -rf".$directory."/prestashop");
            $installPath = $directory;
        }
        else {
            $installPath = 'prestashop';
        }

        /**
         * Fourth Step : CLI install ( if possible )
         * @todo Factorize questions + deals with options params
         */
        if (function_exists('exec') &&  !strpos(ini_get("disable_functions"), "exec") ){

            $output->writeln("<info>Please give information for CLI install : </info>");

            if ( !$domainName ) {
                $domainNameQuestion = new Question("Domain name (default: ' ') "," ");
                $domainName = $helper->ask($input,$output,$domainNameQuestion);
            }

            if (!$dbname) {
                $dbnameQuestion = new Question("Db name : (default: prestashop_console )", "prestashop_console");
                $dbname = $helper->ask($input, $output, $dbnameQuestion);
            }

            if (!$dbuser) {
                $dbuserQuestion = new Question("Db user : (default: root )", "root");
                $dbuser = $helper->ask($input, $output, $dbuserQuestion);
            }

            if ( !$dbpassword ) {
                $dbpasswordQuestion = new Question("Db passord : (default: root )" , "root");
                $dbpassword = $helper->ask($input,$output,$dbpasswordQuestion);
            }

            if ( !$contactEmail ) {
            $contactEmailQuestion = new Question("Admin email : (default: test@example.com )" , "test@example.com");
            $contactEmail = $helper->ask($input,$output,$contactEmailQuestion);
            }

            if ( !$adminpassword ) {
                $adminpassQuestion = new Question("Admin password : (default: test12345678 )" , "test12345678");
                $adminpassword = $helper->ask($input,$output,$adminpassQuestion);
            }

            $output->writeln("<info>Starting CLI install</info>");

            $command = "php ".$installPath."/install/index_cli.php --domain=$domainName  --db_name=$dbname --db_user=$dbuser --db_password=$dbpassword --email=$contactEmail --password=$adminpassword";
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
