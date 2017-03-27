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

namespace Hhennes\PrestashopConsole\Command\Admin\User;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Change admin password
 */
class PasswordCommand extends Command
{
     protected function configure()
    {
        $this
            ->setName('admin:user:change-password')
            ->setDescription('Change admin user password');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $userQuestion = new Question('user email :',false);
        $email = $helper->ask($input,$output,$userQuestion);

         //Error if no employee exists with email
        if ( ! \Employee::employeeExists($email)){
            $output->writeln("<error>Employee with this email not exists");
            return;
        }

        $passwordQuestion = new Question('admin password :','admin123456');
        $password = $helper->ask($input,$output,$passwordQuestion);

        $passwordConfirmQuestion = new Question('confirm admin password :','admin123456');
        $passwordConfirm = $helper->ask($input,$output,$passwordConfirmQuestion);

        if ( $password !== $passwordConfirm ) {
            $output->writeln("<error>Password and password confirmation do not match");
            return;
        }

        $employee = new \Employee();
        $employee->getByEmail($email);
        $employee->passwd = \Tools::encrypt($password);

        try {
            $employee->save();
        } catch (Exception $e) {
             $output->writeln("<error>".$e->getMessage()."</error>");
             return;
        }
        $output->writeln("<info>Password changed with success for user ".$email."</info>");
    }
}
