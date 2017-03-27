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
namespace Hhennes\PrestashopConsole\Command\Configuration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Get All Configuration values
 *
 */
class GetAllCommand extends Command
{
     protected function configure()
    {
        $this
                ->setName('configuration:getAll')
                ->setDescription('get all configuration values');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Load All Configurations
        \Configuration::loadConfiguration();

        //Get All Configuration names (except xml configuration)
        $configurationNames = \Db::getInstance()->executeS("SELECT name FROM "._DB_PREFIX_."configuration WHERE name <> 'PS_INSTALL_XML_LOADERS_ID'");
        $configList = '';
        
        //Get All configuration keys and value
        foreach ( $configurationNames as $config ){
                $configList.= $config['name'] .' '.\Configuration::get($config['name'])."\n";
        }
        $output->write('<info>'.$configList.'</info>');
    }

}