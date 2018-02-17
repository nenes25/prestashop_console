<?php
/**
 * 2007-2018 Hennes Hervé
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
 * @copyright 2007-2018 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
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