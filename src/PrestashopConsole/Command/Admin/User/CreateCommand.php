<?php
/**
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
 * @copyright since 2016 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * https://github.com/nenes25/prestashop_console
 * https://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole\Command\Admin\User;

use Configuration;
use Employee;
use PrestaShop\PrestaShop\Core\Crypto\Hashing;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Tools;
use Validate;

/**
 * Create New Admin User
 * With a role of SuperAdmin
 */
class CreateCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('admin:user:create')
            ->setDescription('Create new admin user')
            ->addOption('email', null, InputOption::VALUE_OPTIONAL, 'Admin email')
            ->addOption('password', null, InputOption::VALUE_OPTIONAL, 'Admin password')
            ->addOption('firstname', null, InputOption::VALUE_OPTIONAL, 'firstname')
            ->addOption('lastname', null, InputOption::VALUE_OPTIONAL, 'lastname');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $email = $input->getOption('email');
        $password = $input->getOption('password');
        $firstname = $input->getOption('firstname');
        $lastname = $input->getOption('lastname');

        if (!Validate::isEmail($email)) {
            $email = $helper->ask($input, $output, $this->getEmailQuestion());
        }

        if (!$this->isValidPassword($password)) {
            $password = $helper->ask($input, $output, $this->getPasswordQuestion());
        }

        if (!Validate::isName($firstname)) {
            $firstname = $helper->ask($input, $output, $this->getCustomerQuestion('firstname'));
        }

        if (!Validate::isName($lastname)) {
            $lastname = $helper->ask($input, $output, $this->getCustomerQuestion('lastname'));
        }

        if (Employee::employeeExists($email)) {
            $output->writeln('<error>Employee with this email already exists</error>');

            return self::RESPONSE_ERROR;
        }

        try {
            $employee = new Employee();
            $employee->active = 1;
            $employee->email = $email;

            // Use PS9 Hashing class if available, fallback to Tools::encrypt for older versions
            if (class_exists('PrestaShop\PrestaShop\Core\Crypto\Hashing')) {
                $crypto = new Hashing();
                $employee->passwd = $crypto->hash($password);
            } elseif (method_exists('Tools', 'encrypt')) {
                $employee->passwd = Tools::encrypt($password);
            } else {
                $employee->passwd = password_hash($password, PASSWORD_DEFAULT);
            }

            $employee->firstname = $firstname;
            $employee->lastname = $lastname;
            $employee->id_lang = Configuration::get('PS_LANG_DEFAULT');
            $employee->id_profile = _PS_ADMIN_PROFILE_;
            $employee->default_tab = 1;
            $employee->bo_theme = 'default';

            if (!$this->validateEmployeeData($employee, $output)) {
                return self::RESPONSE_ERROR;
            }

            if (!$employee->save()) {
                $output->writeln('<error>Failed to create admin user</error>');

                return self::RESPONSE_ERROR;
            }

            $output->writeln('<info>Admin user created successfully!</info>');
            $output->writeln(sprintf('<info>Email: %s</info>', $employee->email));
            $output->writeln(sprintf('<info>ID: %s</info>', $employee->id));
            $output->writeln(sprintf('<info>Name: %s %s</info>', $employee->firstname, $employee->lastname));
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            return self::RESPONSE_ERROR;
        }

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Get employee email question
     *
     * @return Question
     */
    protected function getEmailQuestion(): Question
    {
        $question = new Question('admin email :', false);
        $question->setValidator(function ($answer) {
            if (!Validate::isEmail($answer)) {
                throw new RuntimeException('The email is empty or not valid');
            }

            return $answer;
        });

        return $question;
    }

    /**
     * Get employee password question
     *
     * @return Question
     */
    protected function getPasswordQuestion(): Question
    {
        $question = new Question('admin password :', 'admin123456');
        $question->setHidden(true);
        $question->setValidator(function ($answer) {
            if (!$this->isValidPassword($answer)) {
                throw new RuntimeException('Your password is not valid');
            }

            return $answer;
        });

        return $question;
    }

    /**
     * Validate password (compatible with PS9)
     *
     * @param string $password
     *
     * @return bool
     */
    protected function isValidPassword($password): bool
    {
        // Try PS9 method first
        if (method_exists('Validate', 'isAcceptablePasswordLength')) {
            return Validate::isAcceptablePasswordLength($password);
        }
        if (method_exists('Validate', 'isPasswdAdmin')) {
            return Validate::isPasswdAdmin($password);
        }

        return strlen($password) >= 8;
    }

    /**
     * Get Customer Firstname or lastname question
     *
     * @param string $field firstname|lastname
     *
     * @return Question
     */
    protected function getCustomerQuestion(string $field): Question
    {
        $question = new Question($field . ' :', 'admin');
        $question->setValidator(function ($answer) use ($field) {
            if (!Validate::isName($answer)) {
                throw new RuntimeException($field . ' is not valid');
            }

            return $answer;
        });

        return $question;
    }

    /**
     * Validate employee data before saving
     *
     * @param Employee $employee
     * @param OutputInterface $output
     *
     * @return bool
     */
    protected function validateEmployeeData(Employee $employee, OutputInterface $output): bool
    {
        $errors = [];

        if (empty($employee->email) || !Validate::isEmail($employee->email)) {
            $errors[] = 'Invalid email format';
        }

        if (empty($employee->firstname) || !Validate::isName($employee->firstname)) {
            $errors[] = 'Invalid firstname format';
        }

        if (empty($employee->lastname) || !Validate::isName($employee->lastname)) {
            $errors[] = 'Invalid lastname format';
        }

        if (empty($employee->passwd)) {
            $errors[] = 'Password is required';
        }

        if (!Validate::isUnsignedId($employee->id_lang)) {
            $errors[] = 'Invalid language ID';
        }

        if (!Validate::isUnsignedId($employee->id_profile)) {
            $errors[] = 'Invalid profile ID';
        }

        if (!empty($errors)) {
            $output->writeln('<error>Validation errors:</error>');
            foreach ($errors as $error) {
                $output->writeln('<error>  - ' . $error . '</error>');
            }

            return false;
        }

        return true;
    }
}
