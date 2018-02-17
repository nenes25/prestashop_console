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
 * Commande qui permet d'ajouter des fichiers index.php dans les dossiers manquants
 *
 */
class AddIndexFilesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('dev:add-index-files')
            ->setDescription('Add missing index.php files in directory')
            ->addArgument(
                        'dir', InputArgument::REQUIRED, 'directory to fill ( relative to ps root path)'
            );
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $input->getArgument('dir');
        try {
            if ( !is_dir(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$dir) ) {
                throw new \Exception('directory doesn\'t exists');
            }

            $finder = new Finder();

            //List all directories
            $directories = $finder->directories()->in(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$dir);

            $i = 0;
            foreach ($directories as $directory){

                ${$i} = new Finder();
                //Check if index.php file exists in directory
                $indexFile = ${$i}->files()->in((string)$directory)->depth('==0')->name('index.php');
                //Create if if not
                if ( !sizeof($indexFile )) {
                   $fp = fopen($directory.DIRECTORY_SEPARATOR.'index.php','w+');
                   fputs($fp,$this->_getIndexContent());
                   fclose($fp);
                }
                $i++;
            }


        } catch (Exception $e) {
            $output->writeln("<info>ERROR:" . $e->getMessage() . "</info>");
        }
        $output->writeln("<info>Index files added with success</info>");
    }

    /**
     * Get index.php content file
     * @return string
     */
    protected function _getIndexContent()
    {
        return "
<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Location: ../');
exit;";
    }
}
