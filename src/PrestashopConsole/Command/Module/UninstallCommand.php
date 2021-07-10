<?php
/**
 * 2007-2021 Hennes Hervé
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
 * @copyright 2007-2021 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * https://github.com/nenes25/prestashop_console*
 * https://www.h-hennes.fr/blog/
 *
 */

namespace PrestashopConsole\Command\Module;

use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
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
                                return self::RESPONSE_ERROR;
                            }
                        } catch (PrestaShopException $e) {
                            $output->writeln("<error>Module: '$moduleName' $e->getMessage()</error>");
                            return self::RESPONSE_ERROR;
                        }
                        $output->writeln("<info>Module '$moduleName' uninstalled with success</info>");
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
