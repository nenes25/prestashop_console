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

namespace Hhennes\PrestashopConsole\Command\Module;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Module;
use PrestaShopException;

class InstallCommand extends Command
{
    protected function configure()
    {
        $this->setName('module:install')
                ->setDescription('Install module')
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
                    if (!Module::isInstalled($module->name)) {
                        try {
                            if (!$module->install()) {
                                $output->writeln("<error>Cannot install module: '$moduleName'</error>");
                                return 1;
                            }
                        } catch (PrestaShopException $e) {
                            $output->writeln("<error>Module: '$moduleName' $e->displayMessage()</error>");
                            return 1;
                        }
                        $output->writeln("<info>Module '$moduleName' installed with success</info>");
                    } else {
                        $output->writeln("<comment>Module '$moduleName' is installed</comment>");
                    }
                } else {
                    $output->writeln("<error>Unknow module name '$moduleName' </error>");
                    return 1;
                }
            }
        }
    }
}
