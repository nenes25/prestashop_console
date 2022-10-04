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

namespace Hhennes\PrestashopConsole\Command\Module\Hook;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Module;

/**
 * Commande qui permet de greffer des modules sur un hook
 *
 */
class AddModuleHooksCommand extends Command
{
    protected function configure()
    {
        $this
                ->setName('module:hook:add')
                ->setDescription('Add module to one or several hooks')
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

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument('name');
        $hooks = $input->getArgument('hooks');

        if ($module = Module::getInstanceByName($moduleName)) {
            if (! $module->registerHook($hooks)) {
                $output->writeln('<error>Error during hook assignation</error>');
            } else {
                $output->writeln('<info>Module hooked with success</info>');
            }
        } else {
            $output->writeln('<error>Error the module ' . $moduleName . ' doesn\'t exists</error>');
            return 1;
        }
    }
}
