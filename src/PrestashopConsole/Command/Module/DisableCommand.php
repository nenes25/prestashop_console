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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet d'activer un module
 */
class DisableCommand extends Command
{
    protected function configure(): void
    {
        $this
                ->setName('module:disable')
                ->setDescription('Disable module')
                ->addArgument(
                    'name',
                    InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                    'module name ( separate multiple with spaces )'
                );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if (count($name) > 0) {
            foreach ($name as $moduleName) {
                if ($module = Module::getInstanceByName($moduleName)) {
                    if (Module::isInstalled($module->name)) {
                        try {
                            $module->disable();
                        } catch (PrestaShopException $e) {
                            $outputString = '<error>Error : module ' . $moduleName . ' ' . $e->getMessage() . '<error>';
                            $output->writeln($outputString);

                            return self::RESPONSE_ERROR;
                        }
                        $outputString = '<info>Module ' . $moduleName . ' disable with sucess' . '</info>';
                    } else {
                        $outputString = '<error>Error : module ' . $moduleName . ' is not installed' . '<error>';
                    }
                } else {
                    $outputString = '<error>Error : Unknow module name ' . $moduleName . '</error>';
                }
                $output->writeln($outputString);
            }
        }

        return self::RESPONSE_SUCCESS;
    }
}
