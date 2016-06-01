<?php

/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    Hennes Hervé <contact@h-hennes.fr>
 *  @copyright 2013-2016 Hennes Hervé
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  http://www.h-hennes.fr/blog/
 */

namespace Hhennes\PrestashopConsole\Command\Cache\Smarty;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet de configurer le cache Smarty
 *
 */
class ConfigureCommand extends Command
{

    /**
     * Limitation des donnés à saisir
     * @var type
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
            $output->writeln("Name not allowed");
        } else {
            //Vérification de la valeur
            if (!in_array($value, $this->_allowedNames[$name]['allowed_values'])) {
                $output->writeln("Value not allowed for configuration " . $name);
            } else {
                \Configuration::updateValue($this->_allowedNames[$name]['config_value'], $value);
                $output->writeln("Update configuration " . $name . " with " . $value);
            }
        }
    }

}
