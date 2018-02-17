<?php
/**
 * 2007-2018 Hennes Hervé
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
 * @copyright 2007-2018 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

namespace Hhennes\PrestashopConsole\Command\Dev;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Commande qui permet de lister les overrides en place sur le site
 *
 */
class ListOverridesCommand extends Command
{
     protected function configure()
    {
        $this
            ->setName('dev:list-overrides')
            ->setDescription('List overrides of classes and controllers in the project');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputString = '';
        try {
            $finder = new Finder();
            $finder->files()->in(_PS_OVERRIDE_DIR_)->name('*.php')->notName('index.php');

            foreach ($finder as $file) {
                $outputString.= $file->getRelativePathname()."\n";
            }
        } catch (Exception $e) {
            $output->writeln("<info>ERROR:" . $e->getMessage() . "</info>");
        }
        if ( $outputString == '') {
            $outputString = 'No class or controllers overrides on this project';
        }
        $output->writeln("<info>".$outputString."</info>");
    }
}
