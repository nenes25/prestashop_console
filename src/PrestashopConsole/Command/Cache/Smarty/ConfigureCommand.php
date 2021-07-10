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

namespace PrestashopConsole\Command\Cache\Smarty;

use Configuration;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet de configurer le cache Smarty
 */
class ConfigureCommand extends Command
{
    /**
     * Limitation des donnés à saisir
     *
     * @var array
     */
    protected $_allowedNames = [
        'compile' => ['config_value' => 'PS_SMARTY_FORCE_COMPILE', 'allowed_values' => ['0', '1', '2']],
        'cache' => ['config_value' => 'PS_SMARTY_CACHE', 'allowed_values' => ['0', '1']],
        'cacheType' => ['config_value' => 'PS_SMARTY_CACHING_TYPE', 'allowed_values' => ['filesystem', 'mysql']],
        'clearCache' => ['config_value' => 'PS_SMARTY_CLEAR_CACHE', 'allowed_values' => ['never', 'everytime']],
    ];

    protected function configure()
    {
        $this
                ->setName('cache:smarty:configure')
                ->setDescription('Configure Smarty cache')
                ->addArgument('name', InputArgument::REQUIRED, 'configuration name')
                ->addArgument('value', InputArgument::REQUIRED, 'configuration value');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $value = $input->getArgument('value');

        if (!array_key_exists($name, $this->_allowedNames)) {
            $output->writeln('<error>Name not allowed</error>');

            return self::RESPONSE_ERROR;
        } else {
            //Vérification de la valeur
            if (!in_array($value, $this->_allowedNames[$name]['allowed_values'])) {
                $output->writeln('<error>Value not allowed for configuration ' . $name . '</error>');

                return self::RESPONSE_ERROR;
            } else {
                Configuration::updateValue($this->_allowedNames[$name]['config_value'], $value);
                $output->writeln('<info>Update configuration ' . $name . ' with ' . $value . '</info>');
            }
        }

        return self::RESPONSE_SUCCESS;
    }
}
