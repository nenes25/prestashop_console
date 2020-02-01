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

namespace Hhennes\PrestashopConsole\Command\Module\Tab;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Module;
use Tab;

class RemoveCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('module:tab:remove')
            ->setDescription('remove module admin tab')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'module name'
            )
            ->addArgument(
                'tab',
                InputArgument::REQUIRED,
                'tab class name'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument('name');
        $tab = $input->getArgument('tab');

        try {
            if ($module = Module::getInstanceByName($moduleName)) {
                if ($id_tab = Tab::getIdFromClassName($tab)) {
                    $tabObject = new Tab($id_tab);
                    try {
                        $tabObject->delete();
                    } catch (\Exception $e) {
                        $output->writeln('<error>' . $e->getMessage() . '</error>');
                        return 1;
                    }
                    $output->writeln('<info>Tab ' . $tab . ' removed with success');
                } else {
                    $output->writeln('<error>Tab ' . $tab . ' does not exists</error>');
                    return 1;
                }
            } else {
                $output->writeln('<error>Error the module ' . $moduleName . ' doesn\'t exists</error>');
            }
        } catch (\Exception $e) {
            $output->writeln('<error>Error unable to get information about ' . $moduleName . '</error>');
            return 1;
        }
    }
}
