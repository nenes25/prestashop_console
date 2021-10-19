<?php
/**
 * 2007-2021 Hennes Hervé
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
 * @copyright 2007-2021 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * https://github.com/nenes25/prestashop_console*
 * https://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole\Command\Customer;

use Context;
use Customer;
use Mail;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use PrestaShopException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Validate;

/**
 * Class SendCustomerForgotPasswordCommand
 * This command allow to send forgot password to customer
 */
class SendCustomerForgotPasswordCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('customer:send-forgot-password')
            ->setDescription('Send customer forgot password')
            ->addOption('email', '', InputOption::VALUE_OPTIONAL, 'customer email');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getOption('email');
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

        if (!$this->_sendForgotEmail($customer)) {
            $output->writeln('<error>Unable to send forgot email</error>');

            return self::RESPONSE_ERROR;
        }

        try {
            $customer->stampResetPasswordToken();
            $customer->update();
        } catch (PrestaShopException $e) {
            $output->writeln('<error>Unable to update customer</error>');

            return self::RESPONSE_ERROR;
        }

        $output->writeln('<info>Customer forgot password send with success</info>');

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Email Question
     *
     * @return Question
     */
    protected function _getEmailQuestion(): Question
    {
        $question = new Question('<question>customer email :</question>');
        $question->setValidator(function ($answer) {
            if (null !== $answer && !Validate::isEmail($answer)) {
                throw new RuntimeException('Invalid email');
            }

            return $answer;
        });

        return $question;
    }

    /**
     * Send Forgot email
     *
     * @param Customer $customer
     *
     * @return bool
     */
    protected function _sendForgotEmail(Customer $customer): bool
    {
        $context = Context::getContext();

        $mailParams = [
            '{email}' => $customer->email,
            '{lastname}' => $customer->lastname,
            '{firstname}' => $customer->firstname,
            '{url}' => $context->link->getPageLink('password', true, null, 'token=' . $customer->secure_key . '&id_customer=' . (int) $customer->id . '&reset_token=' . $customer->reset_password_token),
        ];

        return Mail::Send(
            $customer->id_lang,
            'password_query',
            'Password query confirmation',
            $mailParams,
            $customer->email,
            $customer->firstname . ' ' . $customer->lastname
        );
    }
}
