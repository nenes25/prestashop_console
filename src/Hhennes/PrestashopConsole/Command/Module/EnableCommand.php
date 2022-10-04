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

namespace Hhennes\PrestashopConsole\Command\Module;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Module;
use PrestaShopException;

/**
 * Commande qui permet d'activer un module
 *
 */
class EnableCommand extends Command
{
    protected function configure()
    {
        $this->setName('module:enable')
                ->setDescription('Enable module')
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

                        // Exécution de l'action du module
                        try {
                            $module->enable();
                        } catch (PrestaShopException $e) {
                            $outputString = '<error>module ' . $moduleName . ' ' . $e->getMessage() . "</error>";
                            $output->writeln($outputString);
                            return;
                        }
                        $outputString = '<info>Module ' . $moduleName . ' enable with sucess' . "</info>";
                    } else {
                        $outputString = '<error>module ' . $moduleName . ' is not installed' . "<error>";
                    }
                } else {
                    $outputString = '<error>Unknow module name ' . $moduleName . "<error>";
                }
                $output->writeln($outputString);
            }
        }
    }
}
