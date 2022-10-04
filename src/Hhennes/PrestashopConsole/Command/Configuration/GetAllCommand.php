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

namespace Hhennes\PrestashopConsole\Command\Configuration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Configuration;
use Db;

/**
 * Get All Configuration values
 *
 */
class GetAllCommand extends Command
{
    const MAX_LENGTH_CONFIGURATION_VALUE = 130;

    protected function configure()
    {
        $this
                ->setName('configuration:getAll')
                ->setDescription('get all configuration values');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Load All Configurations
        Configuration::loadConfiguration();

        //Get All Configuration names (except xml configuration)
        $configurationNames = Db::getInstance()->executeS("SELECT name FROM "._DB_PREFIX_."configuration WHERE name <> 'PS_INSTALL_XML_LOADERS_ID'");

        $table = new Table($output);
        $table->setHeaders(['Name', 'Value']);
        foreach ($configurationNames as $configuration_name) {
            $configuration_value = Configuration::get($configuration_name['name']);
            if (strlen($configuration_value) > self::MAX_LENGTH_CONFIGURATION_VALUE) {
                $configuration_value = substr($configuration_value, 0, self::MAX_LENGTH_CONFIGURATION_VALUE)." (*)";
            }
            $table->addRow([$configuration_name['name'], $configuration_value]);
        }

        $table->render();
        $output->writeln("(*) : Value truncated");
    }
}
