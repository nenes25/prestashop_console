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
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Employee;

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

        $userQuestion = new Question('user email :', false);
        $email = $helper->ask($input, $output, $userQuestion);

        //Error if no employee exists with email
        if (! Employee::employeeExists($email)) {
            $output->writeln("<error>Employee with this email not exists");
            return;
        }

        $passwordQuestion = new Question('admin password :', 'admin123456');
        $passwordQuestion->setHidden(true);
        $password = $helper->ask($input, $output, $passwordQuestion);

        $passwordConfirmQuestion = new Question('confirm admin password :', 'admin123456');
        $passwordConfirmQuestion->setHidden(true);
        $passwordConfirm = $helper->ask($input, $output, $passwordConfirmQuestion);

        if ($password !== $passwordConfirm) {
            $output->writeln("<error>Password and password confirmation do not match");
            return 1;
        }

        $employee = new Employee();
        $employee->getByEmail($email);
        $employee->passwd = \Tools::encrypt($password);

        try {
            $employee->save();
        } catch (Exception $e) {
            $output->writeln("<error>".$e->getMessage()."</error>");
            return 1;
        }
        $output->writeln("<info>Password changed with success for user ".$email."</info>");
    }
}
