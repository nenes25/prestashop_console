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

/**
 * Récupération d'un fichier des classes pour l'autocomplétion dans l'IDE
 * ( PhpStorm / Netbeans ... )
 * Utilse le dépôt : https://github.com/julienbourdeau/PhpStorm-PrestaShop-Autocomplete
 */
class IdeClassNamesCommand extends Command
{

    const CLASS_NAME_SOURCE = 'https://raw.githubusercontent.com/julienbourdeau/PhpStorm-PrestaShop-Autocomplete/master/autocomplete.php';
    const CLASS_NAME_FILE = 'autocomplete.php';

    protected function configure()
    {
        $this
            ->setName('dev:ide-class-names')
            ->setDescription('Download class names index to resolve autocompletion in IDE');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        //Download content
        $content = file_get_contents(self::CLASS_NAME_SOURCE);
        $fileName= self::CLASS_NAME_FILE;

        if (  $this->getApplication()->getRunAs() == 'php' ) {
            $fileName = '../'.$fileName;
        }

        if ( file_put_contents($fileName, $content) !== false ) {
            $output->writeln('<info>File '.self::CLASS_NAME_FILE.' download with success</info>');
        } else {
            $output->writeln('<error>Unable to create file'.self::CLASS_NAME_FILE.'</error>');
        }
    }
}
