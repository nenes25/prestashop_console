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

namespace Hhennes\PrestashopConsole\Command\Dev;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ListEmptyModulesCommand
 * This command will list all the subdirectories of the "modules" directory
 * And find the one where no real module is installed
 */
class ListEmptyModulesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('dev:list:empty-modules')
            ->setDescription('List subdirectories of "module" directory where no module is really installed')
            ->addOption('remove', null, InputOption::VALUE_NONE, 'remove empty directories')
            ->setHelp(
                "Sometimes you still have modules directory with only emails or translations " . PHP_EOL .
                "and no real module file" . PHP_EOL .
                "Optionally you can remove them also"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputString = '';
        try {
            $finder = new Finder();
            $fileSystem = new Filesystem();
            $hasEmptyModules = false;
            $finder->directories()->in(_PS_MODULE_DIR_)->depth(0);
            foreach ($finder as $directory) {
                if (!is_file($directory->getPathname() . '/' . $directory->getRelativePathname() . '.php')) {
                    $outputString .= 'Empty module directory found for ' . $directory->getRelativePathname() . "\n";
                    $hasEmptyModules = true;
                    if ($input->getOption('remove')) {
                        $fileSystem->remove($directory);
                        $outputString .= 'Directory ' . $directory->getRelativePathname() . " deleted with success \n";
                    }
                }
            }
            if (false === $hasEmptyModules) {
                $outputString .= 'No empty modules found';
            }
        } catch (\Exception $e) {
            $output->writeln("<info>ERROR:" . $e->getMessage() . "</info>");
            return 1;
        }
        $output->writeln("<info>" . $outputString . "</info>");
    }

}