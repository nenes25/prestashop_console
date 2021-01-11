<?php

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
use Mail;
use Context;

/**
 * Class SendCustomerForgotPasswordCommand
 * This command allow to send forgot password to customer
 */
class SendCustomerForgotPasswordCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('customer:send-forgot-password')
            ->setDescription('Send customer forgot password')
            ->addOption('email', '', InputOption::VALUE_OPTIONAL, 'customer email');
    }

    /**
     * @inheritDoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
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
        ;
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
     * Send Forgot email
     * @param Customer $customer
     * @return bool
     */
    protected function _sendForgotEmail(Customer $customer)
    {
        $context = Context::getContext();

        $mailParams = array(
            '{email}' => $customer->email,
            '{lastname}' => $customer->lastname,
            '{firstname}' => $customer->firstname,
            '{url}' => $context->link->getPageLink('password', true, null, 'token=' . $customer->secure_key . '&id_customer=' . (int)$customer->id . '&reset_token=' . $customer->reset_password_token),
        );

        return Mail::Send(
            $customer->id_lang,
            'password_query',
            'Password query confirmation', //@ToDo manage translation for 1.6 and 1.7
            $mailParams,
            $customer->email,
            $customer->firstname . ' ' . $customer->lastname
        );
    }
}
