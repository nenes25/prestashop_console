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

namespace PrestashopConsole\Command\Module;

use Module;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use PrestaShopException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResetCommand extends Command
{
    protected function configure()
    {
        $this->setName('module:reset')
                ->setDescription('Reset module: hard = remove data and reinstall, soft(default) = keep data and reinstall')
                ->addArgument(
                    'name',
                    InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                    'module name ( separate multiple with spaces )'
                )
                ->addOption('type', null, InputOption::VALUE_OPTIONAL, 'hard|soft(default)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $type = $input->getOption('type');

        if (count($name) > 0) {
            foreach ($name as $moduleName) {
                if ($module = Module::getInstanceByName($moduleName)) {
                    if (Module::isInstalled($module->name)) {
                        try {
                            $error = false;
                            switch ($type) {
                                case 'hard':
                                    if ($module->uninstall()) {
                                        if (!$module->install()) {
                                            $output->writeln("<error>Cannot install module: '$moduleName'</error>");
                                            $error = true;
                                        }
                                    } else {
                                        $output->writeln("<error>Cannot uninstall module: '$moduleName'</error>");
                                        $error = true;
                                    }
                                    break;
                                case 'soft':
                                default:
                                    if (method_exists($module, 'reset')) {
                                        if (!$module->reset()) {
                                            $output->writeln("<error>Cannot reset module: '$moduleName'</error>");
                                            $error = true;
                                        }
                                    } else {
                                        $output->writeln("<error>Module '$moduleName' doesnt support soft reset</error>");
                                        $error = true;
                                    }
                                    break;
                            }
                        } catch (PrestaShopException $e) {
                            $output->writeln("<error>Module: '$moduleName' $e->getMessage()</error>");
                            $error = true;
                        }
                        if (!$error) {
                            $output->writeln("<info>Module '$moduleName' reset with success</info>");
                        } else {
                            return self::RESPONSE_ERROR;
                        }
                    } else {
                        $output->writeln("<comment>Module '$moduleName' is uninstalled</comment>");
                    }
                } else {
                    $output->writeln("<error>Unknow module name '$moduleName' </error>");

                    return self::RESPONSE_ERROR;
                }
            }
        }

        return self::RESPONSE_SUCCESS;
    }
}
