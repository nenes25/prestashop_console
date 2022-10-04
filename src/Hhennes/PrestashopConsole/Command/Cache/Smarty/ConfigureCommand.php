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

namespace Hhennes\PrestashopConsole\Command\Cache\Smarty;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Configuration;

/**
 * Commande qui permet de configurer le cache Smarty
 *
 */
class ConfigureCommand extends Command
{

    /**
     * Limitation des donnés à saisir
     * @var array $_allowedNames
     */
    protected $_allowedNames = array(
        'compile' => array('config_value' => 'PS_SMARTY_FORCE_COMPILE', 'allowed_values' => array('0', '1', '2')),
        'cache' => array('config_value' => 'PS_SMARTY_CACHE', 'allowed_values' => array('0', '1')),
        'cacheType' => array('config_value' => 'PS_SMARTY_CACHING_TYPE', 'allowed_values' => array('filesystem', 'mysql')),
        'clearCache' => array('config_value' => 'PS_SMARTY_CLEAR_CACHE', 'allowed_values' => array('never', 'everytime'))
    );

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
            $output->writeln("<error>Name not allowed</error>");
            return 1;
        } else {
            //Vérification de la valeur
            if (!in_array($value, $this->_allowedNames[$name]['allowed_values'])) {
                $output->writeln("<error>Value not allowed for configuration " . $name."</error>");
                return 1;
            } else {
                Configuration::updateValue($this->_allowedNames[$name]['config_value'], $value);
                $output->writeln("<info>Update configuration " . $name . " with " . $value."</info>");
            }
        }
    }
}
