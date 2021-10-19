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

use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use PrestaShopException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tools;
use WebserviceKey;
use WebserviceRequest;

/**
 * Class CreateKey
 * Create an api key in prestashop
 */
class CreateKeyCommand extends Command
{
    /** @var string Option Key */
    const OPTION_KEY = 'key';

    /** @var string Option description */
    const OPTION_DESCRIPTION = 'description';

    protected function configure(): void
    {
        $this
            ->setName('webservice:key:create')
            ->setDescription('Create a webservice key')
            ->addOption(
                self::OPTION_KEY,
                'k',
                InputOption::VALUE_OPTIONAL,
                'Force a key ( get a new one by default)'
            )
            ->addOption(
                self::OPTION_DESCRIPTION,
                'd',
                InputOption::VALUE_OPTIONAL,
                'Api Key description',
                'Api key created by prestashop console'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $apiKey = $input->getOption(self::OPTION_KEY);
        $apiDescription = $input->getOption(self::OPTION_DESCRIPTION);

        if (null !== $apiKey) {
            if (!$this->_validateWebserviceKey($apiKey)) {
                $output->writeln('<error>The api key is invalid ( 32 characters required)</error>');

                return self::RESPONSE_ERROR;
            }
            if (WebserviceKey::keyExists(pSQL($apiKey))) {
                $output->writeln('<error>The api key already exists</error>');

                return self::RESPONSE_ERROR;
            }
        }

        $webserviceKey = new WebserviceKey();
        $webserviceKey->key = $apiKey ? $apiKey : $this->_generateWebserviceKey();
        $webserviceKey->description = $apiDescription;

        try {
            $webserviceKey->save();
            WebserviceKey::setPermissionForAccount($webserviceKey->id, $this->_getPermissions());
        } catch (PrestaShopException $e) {
            $output->writeln('<error>An error occurs while saving webservice key</error>');

            return self::RESPONSE_ERROR;
        }

        $output->writeln('<info>Webservice key created with success</info>', OutputInterface::VERBOSITY_VERBOSE);
        $output->writeln($webserviceKey->key);

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Get permission for webservice Key
     * For now the key will have all accesses
     *
     * @return array
     */
    protected function _getPermissions(): array
    {
        $resources = WebserviceRequest::getResources();
        $methods = ['GET', 'PUT', 'POST', 'DELETE', 'HEAD'];
        $permissions = [];
        foreach ($resources as $resource => $params) {
            foreach ($methods as $method) {
                if (array_key_exists('forbidden_method', $params) && in_array($method, $params['forbidden_method'])) {
                    continue;
                }
                $permissions[$resource][$method] = 1;
            }
        }

        return $permissions;
    }

    /**
     * Get a random api key
     *
     * @return string
     */
    protected function _generateWebserviceKey(): string
    {
        return Tools::passwdGen(32, 'NO_NUMERIC');
    }

    /**
     * Validate that provided key is valid
     *
     * @param string $key
     *
     * @return bool
     */
    protected function _validateWebserviceKey($key): bool
    {
        return (bool) preg_match('/^[A-Z_0-9-]{32}$/', $key);
    }
}
