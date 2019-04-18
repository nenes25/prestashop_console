<?php
/**
 * 2002-2019 ADVISA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is available
 * through the world-wide-web at this URL: http://www.opensource.org/licenses/OSL-3.0
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to mage@advisa.fr so we can send you a copy immediately.
 *
 * @author ADVISA
 * @copyright 2002-2019 ADVISA
 * @license http://www.opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Hhennes\PrestashopConsole\Command\Dev;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Db;

class AnonymiseCustomerCommand extends Command
{
    protected $_allowedTypes = array('customers', 'addresses', 'newsletter');
    protected $_excludedEmail = null;

    protected function configure()
    {
        $this
            ->setName('dev:anonymise:customer')
            ->setDescription('Anonymise Customer information')
            ->addOption('type', null, InputOption::VALUE_OPTIONAL, 'customers(default)|addresses|newsletter', 'customers')
            ->addOption('exclude-emails', null, InputOption::VALUE_OPTIONAL, 'emails to exclude separated by commas')
            ->addOption('names', null, InputOption::VALUE_OPTIONAL, 'anonymise names (default none ) use only for customers');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getOption('type');
        $this->_excludedEmail = $input->getOption('exclude-emails');
        if (!$type || !in_array($type, $this->_allowedTypes)) {
            $type = 'customers';
        }
        $method = '_anonymise' . ucfirst(strtolower($type));
        $message = $this->$method($input);
        $output->writeln($message);
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    protected function _anonymiseCustomers(InputInterface $input)
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
        } catch (\PrestaShopDatabaseException $e) {
            return '<error>' . strip_tags($e->getMessage()) . '</error>';
        }

        return '<info>Customer anonymised with success</info>';
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    protected function _anonymiseAddresses(InputInterface $input)
    {

        $sqlCond = '';
        if ($this->_excludedEmail) {
            $sqlCond .= 'WHERE id_customer NOT IN ( SELECT id_customer FROM '._DB_PREFIX_.'customer WHERE email IN (';
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
            Db::getInstance()->execute("UPDATE " . _DB_PREFIX_ . "address SET lastname = '" . $this->_randomString() . "', firstname = '" . $this->_randomString() . "' " . $sqlCond);
        }catch (\PrestaShopDatabaseException $e) {
            return '<error>' . strip_tags($e->getMessage()) . '</error>';
        }

        return '<info>Addresses anonymised with success</info>';

    }

    /**
     * @param InputInterface $input
     * @ToDo noms de tables différents en fonction de ps 1.6 et 1.7
     */
    protected function _anonymiseNewsletter(InputInterface $input)
    {
        if ( version_compare('1.7',_PS_VERSION_) == 1) {
            $table = 'newsletter';
        } else {
            $table = 'emailsubscription';
        }

        $sqlCond ='';
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
            Db::getInstance()->execute("UPDATE " . _DB_PREFIX_ . $table." SET email = CONCAT(MD5(email),'@fake-email.com') ". $sqlCond);
        }catch (\PrestaShopDatabaseException $e) {
            return '<error>' . strip_tags($e->getMessage()) . '</error>';
        }

        return '<info>Newsletter subscriber anonymised with success</info>';

    }

    /**
     * @param int $length
     * @return string
     */
    function _randomString($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $length; $i++) {
            $randstring .= $characters[rand(1, strlen($characters))];
        }
        return $randstring;
    }

}