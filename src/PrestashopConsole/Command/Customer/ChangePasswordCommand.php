<?php
/**
 * 2007-2020 Hennes Hervé
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
 * @copyright 2007-2020 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole\Command\Customer;


use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Exception\RuntimeException;
use PrestaShopException;
use Customer;
use Validate;
use Tools;

/**
 * Class ChangeCustomerPassword
 * This command allow to change frontend customers passwords
 */
class ChangePasswordCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('customer:change-password')
            ->setDescription('Change frontend customer password')
            ->addOption('email', '', InputOption::VALUE_OPTIONAL, 'customer email')
            ->addOption('password', '', InputOption::VALUE_OPTIONAL, 'customer password');
    }

    /**
     * @inheritDoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $email =  $input->getOption('email');
        $password = $input->getOption('password');

        $questionHelper = $this->getHelper('question');

        if (null === $email || empty($email) || !Validate::isEmail($email)) {
            $email = $questionHelper->ask($input, $output, $this->_getEmailQuestion());
        }

        $customer = new Customer();
        $customer->getByEmail($email);
        if (!Validate::isLoadedObject($customer)) {
            $output->writeln('<error>There is no account registered for this email.</error>');
            return self::RESPONSE_ERROR;
        }

        if (null === $password || empty($password)) {
            $password = $questionHelper->ask($input, $output, $this->_getPasswordQuestion());
        }

        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            $password = Tools::encrypt($password);
        } else {
            $hashing = new \PrestaShop\PrestaShop\Core\Crypto\Hashing();
            $password = $hashing->hash($password);
        }

        try {
            $customer->passwd = $password;
            $customer->save();
        } catch (PrestaShopException $e) {
            $output->writeln('<error>Unable to update customer password.</error>');
            return self::RESPONSE_ERROR;
        }

        $output->writeln('<info>Customer password updated with success</info>');
        return self::RESPONSE_SUCCESS;
    }


    /**
     * Email Question
     * @return Question
     */
    protected function _getEmailQuestion()
    {
        $question = new Question('<question>customer email :</question>');
        $question->setValidator(function ($answer) {
            if (null !== $answer && !Validate::isEmail($answer)) {
                throw new RuntimeException("Invalid email");
            }
            return $answer;
        });
        return $question;
    }

    /**
     * Password Question
     * @return Question
     */
    protected function _getPasswordQuestion()
    {
        $question = new Question('<question>customer password :</question>');
        $question->setHidden(true);
        $question->setValidator(function ($answer) {
            if (null === $answer) {
                throw new RuntimeException("Invalid email");
            }
            return $answer;
        });
        return $question;
    }
}
