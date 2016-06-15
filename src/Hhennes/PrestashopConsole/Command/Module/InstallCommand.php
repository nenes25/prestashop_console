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
 * @author    Mariusz Mielnik <mariusz@ecbox.pl>
 * @copyright 2013-2016 Mariusz Mielnik
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  http://www.ecbox.pl
 */

namespace Hhennes\PrestashopConsole\Command\Module;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    protected function configure()
    {
        $this->setName('module:install')
            ->setDescription('Install module')
            ->addArgument(
                'name', InputArgument::OPTIONAL, 'module name'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $name = $input->getArgument('name');

        if ($module = \Module::getInstanceByName($name)) {

            if (!\Module::isInstalled($module->name)) {

                // Exécution de l'action du module
                try {
                    if (!$module->install()) {
                        $output->writeln('Error : cannot install module' . $name);
                        return;
                    }
                } catch (\PrestashopException $e) {
                    $output->writeln('Error : module ' . $name . ' ' . $e->getMesage());
                    return;
                }
                $outputString = 'Module ' . $name . ' installed with sucess' . "\n";
            } else {
                $outputString = 'Error : module ' . $name . ' is installed' . "\n";
            }
        } else {
            $outputString = 'Error : Unknow module name ' . $name . "\n";
        }
        $output->writeln($outputString);
    }
}
