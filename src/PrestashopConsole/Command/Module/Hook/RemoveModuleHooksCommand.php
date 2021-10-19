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

namespace PrestashopConsole\Command\Module\Hook;

use Module;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet de supprimer les modules d'un hook
 */
class RemoveModuleHooksCommand extends Command
{
    protected function configure(): void
    {
        $this
                ->setName('module:hook:remove')
                ->setDescription('Remove module to one or several hooks')
                ->addArgument(
                    'name',
                    InputArgument::REQUIRED,
                    'module name'
                )
                ->addArgument(
                    'hooks',
                    InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                    'hooks name ( separate multiple with spaces )'
                );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $moduleName = $input->getArgument('name');
        $hooks = $input->getArgument('hooks');

        if ($module = Module::getInstanceByName($moduleName)) {
            if (sizeof($hooks)) {
                foreach ($hooks as $hook) {
                    if (!$module->unregisterHook($hook)) {
                        $output->writeln('<error>Error during hook remove from hook ' . $hook . '</error>');

                        return self::RESPONSE_ERROR;
                    } else {
                        $output->writeln('<info>Module remove from hook ' . $hook . ' with success</info>');
                    }
                }
            } else {
                $output->writeln('<error>Not hooks given for ' . $moduleName . '</error>');

                return self::RESPONSE_ERROR;
            }
        } else {
            $output->writeln('<error>Error the module ' . $moduleName . ' doesn\'t exists</error>');

            return self::RESPONSE_ERROR;
        }

        return self::RESPONSE_SUCCESS;
    }
}
