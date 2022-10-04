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
 *
 * https://github.com/nenes25/prestashop_console
 * https://www.h-hennes.fr/blog/
 */

namespace Hhennes\PrestashopConsole\Command\Dev;

use PrestaShopDatabaseException;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Db;

class AnonymizeCustomerCommand extends Command
{
    /**
     * @var array Allowed types of anonymizations
     */
    protected $_allowedTypes = array('all', 'customers', 'addresses', 'newsletter', 'customer_thread');

    /**
     * @var null|string List of email to not anonymize
     */
    protected $_excludedEmail = null;

    /**
     * @var string Fake domain email
     */
    protected $_fakeEmailsDomain = 'anonymized-email.com';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('dev:anonymize:customer')
            ->setDescription('Anonymize Customer information')
            ->addOption(
                'type',
                null,
                InputOption::VALUE_OPTIONAL,
                'allowed values '.implode(' | ', $this->_allowedTypes)
            )
            ->addOption(
                'exclude-emails',
                null,
                InputOption::VALUE_OPTIONAL,
                'emails to exclude separated by commas'
            )
            ->addOption(
                'names',
                null,
                InputOption::VALUE_OPTIONAL,
                'anonymize names (default no ) use only for customers'
            )
            ->setHelp('This command will anonymize customer related data (lastname,firstname,email ) without erasing them');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getOption('type');
        $excludes = $input->getOption('exclude-emails');

        //Interactive mod
        if (null === $type) {
            $questionHelper = $this->getHelper('question');
            $type = $questionHelper->ask($input, $output, $this->_getTypeQuestion());
            if (null === $excludes) {
                $excludes = $questionHelper->ask($input, $output, $this->getEmailQuestion());
            }
        }

        $this->_excludedEmail = $excludes;
        if (!$type || !in_array($type, $this->_allowedTypes)) {
            $type = 'customers';
        }

        $method = '_anonymize' . ucfirst(strtolower(str_replace('_', '', $type)));
        $message = $this->$method($input);
        $output->writeln($message);
    }

    /**
     * Anonymize all content
     * @param InputInterface $input
     * @return string
     */
    protected function _anonymizeAll(InputInterface $input)
    {
        $message = '';
        $message .= $this->_anonymizeCustomers($input) . "\n";
        $message .= $this->_anonymizeAddresses($input) . "\n";
        $message .= $this->_anonymizeNewsletter($input) . "\n";
        $message .= $this->_anonymizeCustomerthread($input);

        return $message;
    }

    /**
     * Anonymize customer data
     * @param InputInterface $input
     * @return string
     */
    protected function _anonymizeCustomers(InputInterface $input)
    {
        $anonymiseName = $input->getOption('names');
        $sqlCond = $sqlUpd = '';

        if ($this->_excludedEmail) {
            $sqlCond .= 'WHERE email NOT IN (';
            $emails = explode(',', $this->_excludedEmail);
            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $sqlCond .= "'" . pSQL($email) . "',";
                }
            }
            $sqlCond = rtrim($sqlCond, ',');
            $sqlCond .= ')';
        }

        if ($anonymiseName) {
            $sqlUpd = " , lastname = '" . $this->_randomString() . "', firstname = '" . $this->_randomString() . "' ";
        }

        try {
            Db::getInstance()->execute("UPDATE " . _DB_PREFIX_ . "customer SET email = CONCAT(MD5(email),'@fake-email.com') " . $sqlUpd . ' ' . $sqlCond);
        } catch (PrestaShopDatabaseException $e) {
            return '<error>' . strip_tags($e->getMessage()) . '</error>';
        }

        return '<info>Customer anonymized with success</info>';
    }

    /**
     * Anonymize address data
     * @param InputInterface $input
     * @return string
     */
    protected function _anonymizeAddresses(InputInterface $input)
    {
        $sqlCond = '';
        if ($this->_excludedEmail) {
            $sqlCond .= 'WHERE id_customer NOT IN ( 
            SELECT id_customer FROM ' . _DB_PREFIX_ . 'customer WHERE email IN (';
            $emails = explode(',', $this->_excludedEmail);
            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $sqlCond .= "'" . pSQL($email) . "',";
                }
            }
            $sqlCond = rtrim($sqlCond, ',');
            $sqlCond .= '))';
        }

        try {
            Db::getInstance()->execute(
                "UPDATE " . _DB_PREFIX_ . "address 
                    SET lastname = '" . $this->_randomString() . "', firstname = '" . $this->_randomString() . "' "
                . $sqlCond
            );
        } catch (PrestaShopDatabaseException $e) {
            return '<error>' . strip_tags($e->getMessage()) . '</error>';
        }

        return '<info>Addresses anonymized with success</info>';
    }

    /**
     * Anonymize newsletter data
     * @param InputInterface $input
     * @return string
     */
    protected function _anonymizeNewsletter(InputInterface $input)
    {
        if (version_compare('1.7', _PS_VERSION_) == 1) {
            $table = 'newsletter';
        } else {
            $table = 'emailsubscription';
        }

        $sqlCond = '';
        if ($this->_excludedEmail) {
            $sqlCond .= 'WHERE email NOT IN (';
            $emails = explode(',', $this->_excludedEmail);
            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $sqlCond .= "'" . pSQL($email) . "',";
                }
            }
            $sqlCond = rtrim($sqlCond, ',');
            $sqlCond .= ')';
        }

        try {
            Db::getInstance()->execute(
                "UPDATE " . _DB_PREFIX_ . $table . " 
                    SET email = CONCAT(MD5(email),'@" . $this->_fakeEmailsDomain . "') "
                . $sqlCond
            );
        } catch (PrestaShopDatabaseException $e) {
            return '<error>' . strip_tags($e->getMessage()) . '</error>';
        }

        return '<info>Newsletter subscriber anonymized with success</info>';
    }

    /**
     * Anonymize customerThread data
     * @param InputInterface $input
     * @return string
     */
    protected function _anonymizeCustomerthread(InputInterface $input)
    {
        $sqlCond = '';
        if ($this->_excludedEmail) {
            $sqlCond .= 'WHERE email NOT IN (';
            $emails = explode(',', $this->_excludedEmail);
            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $sqlCond .= "'" . pSQL($email) . "',";
                }
            }
            $sqlCond = rtrim($sqlCond, ',');
            $sqlCond .= ')';
        }

        try {
            Db::getInstance()->execute(
                "UPDATE " . _DB_PREFIX_ . "customer_thread 
                    SET email = CONCAT(MD5(email),'@" . $this->_fakeEmailsDomain . "') "
                . $sqlCond
            );
        } catch (PrestaShopDatabaseException $e) {
            return '<error>' . strip_tags($e->getMessage()) . '</error>';
        }

        return '<info>Customer thread anonymized with success</info>';
    }


    /**
     * Get Type Question
     * @return Question
     */
    protected function _getTypeQuestion()
    {
        $cleanTypeQuestion = new Question('<question>Clean Type :</question>');
        $allowedTypes = $this->_allowedTypes;
        $cleanTypeQuestion->setAutocompleterValues($allowedTypes);
        $cleanTypeQuestion->setValidator(function ($answer) use ($allowedTypes) {
            if ($answer !== null && !in_array($answer, $allowedTypes)) {
                throw new RuntimeException('The field type must be part of the suggested');
            }
            return $answer;
        });

        return $cleanTypeQuestion;
    }

    /**
     * Get email question
     * @return Question
     */
    protected function getEmailQuestion()
    {
        $emailQuestion = new Question('<question>Exclude emails from anonymization ? (separated by commas)</question>');
        $emailQuestion->setValidator(function ($answer) {
            $allAnswers = explode(',', $answer);
            foreach ($allAnswers as $allAnswer) {
                if (!empty($allAnswer) && !filter_var($allAnswer, FILTER_VALIDATE_EMAIL)) {
                    throw  new RuntimeException("Invalid email  in exclude email");
                }
                return $answer;
            }
        });

        return $emailQuestion;
    }

    /**
     * Randomize email for db integration
     * @param string $email
     * @return string
     */
    protected function _randomizeEmail($email)
    {
        $randomEmail = md5(sha1($email) . $this->_randomString());
        return $randomEmail . '@' . $this->_fakeEmailsDomain;
    }

    /**
     * @param int $length
     * @return string
     */
    public function _randomString($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $length; $i++) {
            $randstring .= $characters[rand(1, strlen($characters))];
        }
        return $randstring;
    }
}
