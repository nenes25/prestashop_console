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

namespace Hhennes\PrestashopConsole\Command\Module\Hook;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Module;

/**
 * Commande qui permet de lister les hooks d'un module
 *
 */
class ListModuleHooksCommand extends Command
{
    protected function configure()
    {
        $this
                ->setName('module:hook:list')
                ->setDescription('Get modules list')
                ->addArgument(
                    'name',
                    InputArgument::REQUIRED,
                    'module name'
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

        if ($module = Module::getInstanceByName($moduleName)) {

            //Possible hook list
            $possibleHooksList = $module->getPossibleHooksList();
            $moduleHooks = array();

            foreach ($possibleHooksList as $hook) {
                $isHooked = (int)$module->getPosition($hook['id_hook']);

                if ($isHooked != 0) {
                    $moduleHooks[] = $hook['name'];
                }
            }
            if (sizeof($moduleHooks)) {
                $output->writeln('<info>The module ' . $moduleName . ' is linked on the folowing hooks :' . rtrim(implode(', ', $moduleHooks), ', ') . '</info>');
            } else {
                $output->writeln('<info>The module is not hooked</info>');
            }
        } else {
            $output->writeln('<error>Error the module ' . $moduleName . ' doesn\'t exists</error>');
            return 1;
        }
    }
}
