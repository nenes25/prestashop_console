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

namespace Hhennes\PrestashopConsole\Command\Admin\User;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Configuration;
use Employee;
use PrestaShopException;
use Tools;

/**
 * Create New Admin User
 * With a role of SuperAdmin
 */
class CreateCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('admin:user:create')
            ->setDescription('Create new admin user')
            ->addOption('email', null, InputOption::VALUE_OPTIONAL, 'Admin email')
            ->addOption('password', null, InputOption::VALUE_OPTIONAL, 'Admin password')
            ->addOption('firstname', null, InputOption::VALUE_OPTIONAL, 'firstname')
            ->addOption('lastname', null, InputOption::VALUE_OPTIONAL, 'lastname');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        if (!$email = $input->getOption('email')) {
            $userQuestion = new Question('admin email :', false);
            $email = $helper->ask($input, $output, $userQuestion);
        }

        if (!$password = $input->getOption('password')) {
            $passwordQuestion = new Question('admin password :', 'admin123456');
            $passwordQuestion->setHidden(true);
            $password = $helper->ask($input, $output, $passwordQuestion);
        }

        if (!$firstname = $input->getOption('firstname')) {
            $firstnameQuestion = new Question('firstname :', 'admin');
            $firstname = $helper->ask($input, $output, $firstnameQuestion);
        }

        if (!$lastname = $input->getOption('lastname')) {
            $lastnameQuestion = new Question('lastname :', 'admin');
            $lastname = $helper->ask($input, $output, $lastnameQuestion);
        }

        //Error if employee with same email already exists
        if (Employee::employeeExists($email)) {
            $output->writeln("<error>Employee with this email already exists");
            return;
        }

        $employee = new Employee();
        $employee->active = 1;
        $employee->email = $email;
        $employee->passwd = Tools::encrypt($password);
        $employee->firstname = $firstname;
        $employee->lastname = $lastname;
        $employee->id_lang = Configuration::get('PS_LANG_DEFAULT');
        $employee->id_profile = _PS_ADMIN_PROFILE_;
        $employee->default_tab = 1;
        $employee->bo_theme = 'default';

        try {
            $employee->save();
        } catch (PrestaShopException $e) {
            $output->writeln("<error>".$e->getMessage()."</error>");
            return;
        }

        $output->writeln("<info>New user ".$email." created</info>");
    }
}
