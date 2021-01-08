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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Exception\RuntimeException;
use Customer;
use PrestaShopException;
use Validate;
use Tools;

/**
 * Class Create
 * Command sample description
 */
class CreateCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('customer:create')
            ->setDescription('Create a new frontend customer')
            ->setHelp('Create a new frontend customer')
            ->addOption('email', '', InputOption::VALUE_OPTIONAL, 'customer email')
            ->addOption('password', '', InputOption::VALUE_OPTIONAL, 'customer password')
            ->addOption('firstname', '', InputOption::VALUE_OPTIONAL, 'customer firstname')
            ->addOption('lastname', '', InputOption::VALUE_OPTIONAL, 'customer lastname')
            ->addOption('id_shop', '', InputOption::VALUE_OPTIONAL, 'customer shop id', 1);
    }

    /**
     * @inheritDoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getOption('email');
        $password = $input->getOption('password');
        $firstname = $input->getOption('firstname');
        $lastname = $input->getOption('lastname');
        $id_shop = $input->getOption('id_shop');

        /** @var $questionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        if (null === $email || !Validate::isEmail($email)) {
            $email = $questionHelper->ask($input, $output, $this->_getEmailQuestion());
        }
        if (null === $password || empty($password)) {
            $password = $questionHelper->ask($input, $output, $this->_getPasswordQuestion());
            if (version_compare(_PS_VERSION_, 1.7, '<')) {
                $password = Tools::encrypt($password);
            } else {
                $hashing = new \PrestaShop\PrestaShop\Core\Crypto\Hashing();
                $password = $hashing->hash($password);
            }
        }
        if (null === $firstname || !Validate::isCustomerName($firstname)) {
            $firstname = $questionHelper->ask($input, $output, $this->_getFirstnameQuestion());
        }
        if (null === $lastname || !Validate::isCustomerName($lastname)) {
            $lastname = $questionHelper->ask($input, $output, $this->_getLastNameQuestion());
        }

        if (null !== $id_shop && !Validate::isUnsignedInt($id_shop)) {
            $id_shop = $id_shop;
        }

        try {
            $customer = new Customer();
            $customer->email = $email;
            $customer->passwd = $password;
            $customer->firstname = $firstname;
            $customer->lastname = $lastname;
            $customer->id_shop = $id_shop;
            $customer->save();
        } catch (PrestaShopException $e) {
            $output->writeln('<error>Unable to create customer');
            return 1;
        }

        $output->writeln('<info>new customer '.$email.' created with success</info>');
        return 0;
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

    /**
     * Firstname question
     * @return Question
     */
    protected function _getFirstnameQuestion()
    {
        $question = new Question('<question>customer firstname :</question>');
        $question->setValidator(function ($answer) {
            if (null !== $answer && !Validate::isCustomerName($answer)) {
                throw new RuntimeException("Invalid firstname");
            }
            return $answer;
        });
        return $question;
    }

    /**
     * Lastname question
     * @return Question
     */
    protected function _getLastNameQuestion()
    {
        $question = new Question('<question>customer lastname :</question>');
        $question->setValidator(function ($answer) {
            if (null !== $answer && !Validate::isCustomerName($answer)) {
                throw new RuntimeException("Invalid lastname");
            }
            return $answer;
        });
        return $question;
    }
}
