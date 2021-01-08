<?php
/**
 * 2007-2019 PrestaShop
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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    Mariusz Mielnik <mariusz@ecbox.pl>
 * @copyright 2013-2019 Mariusz Mielnik
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 *  http://www.ecbox.pl
 */

namespace Hhennes\PrestashopConsole\Command\Module;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Module;
use PrestaShopException;

class UninstallCommand extends Command
{
    protected function configure()
    {
        $this->setName('module:uninstall')
                ->setDescription('Uninstall module')
                ->addArgument(
                    'name',
                    InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                    'module name ( separate multiple with spaces )'
                );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        if (count($name) > 0) {
            foreach ($name as $moduleName) {
                if ($module = Module::getInstanceByName($moduleName)) {
                    if (Module::isInstalled($module->name)) {
                        try {
                            if (!$module->uninstall()) {
                                $output->writeln("<error>Cannot uninstall module: '$moduleName'</error>");
                                return 1;
                            }
                        } catch (PrestaShopException $e) {
                            $output->writeln("<error>Module: '$moduleName' $e->getMessage()</error>");
                            return 1;
                        }
                        $output->writeln("<info>Module '$moduleName' uninstalled with success</info>");
                    } else {
                        $output->writeln("<comment>Module '$moduleName' is uninstalled</comment>");
                    }
                } else {
                    $output->writeln("<error>Unknow module name '$moduleName' </error>");
                    return 1;
                }
            }
        }
    }
}
