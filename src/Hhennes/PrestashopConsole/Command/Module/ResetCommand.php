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

class ResetCommand extends Command
{
    protected function configure()
    {
        $this->setName('module:reset')
            ->setDescription('Reset module: hard = remove data and reinstall, soft(default) = keep data and reinstall')
            ->addArgument('name', InputArgument::REQUIRED, 'module name')
            ->addArgument('type', InputArgument::OPTIONAL, 'hard|soft(default)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $name = $input->getArgument('name');
        $type = $input->getArgument('type');

        if ($module = \Module::getInstanceByName($name)) {
            if (\Module::isInstalled($module->name)) {
                try {

                    switch ($type) {
                        case 'hard':
                            if ($module->uninstall()) {
                                if (!$module->install()) {
                                    $output->writeln('Cannot install module: ' . $name);
                                    return;
                                }
                            } else {
                                $output->writeln('Cannot uninstall module: ' . $name);
                                return;
                            }
                            break;
                        case 'soft':
                        default:
                            if (method_exists($module, 'reset')) {
                                if (!$module->reset()) {
                                    $output->writeln('Cannot reset module: ' . $name);
                                    return;
                                }
                            } else {
                                $output->writeln('Error : Module ' . $name . ' doesnt support soft reset');
                                return;
                            }
                            break;
                    }

                } catch (\PrestashopException $e) {
                    $output->writeln('Error : module ' . $name . ' ' . $e->getMesage());
                    return;
                }
                $outputString = 'Module ' . $name . ' reset ' . $type . ' with success';
            } else {
                $outputString = 'Error : module ' . $name . ' is uninstalled';
            }
        } else {
            $outputString = 'Error : Unknow module name ' . $name;
        }
        $output->writeln($outputString);
    }
}
