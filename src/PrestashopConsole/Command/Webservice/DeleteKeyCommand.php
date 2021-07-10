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

namespace PrestashopConsole\Command\Webservice;

use Db;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use PrestaShopException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WebserviceKey;

/**
 * Class DeleteKey
 * Delete webservice key
 */
class DeleteKeyCommand extends Command
{
    /** @var string Argument Key */
    const ARGUMENT_KEY = 'key';

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('webservice:key:delete')
            ->setDescription('Delet a webservice key')
            ->addArgument(
                self::ARGUMENT_KEY,
                InputArgument::REQUIRED,
                'Webservice key to delete'
            );
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $apiKey = $input->getArgument(self::ARGUMENT_KEY);

        if ((empty($apiKey) || !$this->_validateWebserviceKey($apiKey))) {
            $output->writeln('<error>The api key is invalid ( 32 characters required)</error>');

            return self::RESPONSE_ERROR;
        }
        if (!WebserviceKey::keyExists(pSQL($apiKey))) {
            $output->writeln('<error>The api key does not exists</error>');

            return self::RESPONSE_ERROR;
        }

        $idKey = Db::getInstance()->getValue('
                SELECT id_webservice_account 
                FROM ' . _DB_PREFIX_ . "webservice_account
                WHERE `key`= '" . pSQL($apiKey) . "' 
        ");

        $webserviceKey = new WebserviceKey($idKey);
        try {
            $webserviceKey->delete();
        } catch (PrestaShopException $e) {
            $output->writeln('<error>An error occurs while saving webservice key</error>');

            return self::RESPONSE_ERROR;
        }
        $output->writeln('<info>Webservice key deleted with success</info>');

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Validate that provided key is valid
     *
     * @param string $key
     *
     * @return bool
     */
    protected function _validateWebserviceKey($key)
    {
        return (bool) preg_match('/^[A-Z_0-9-]{32}$/', $key);
    }
}
