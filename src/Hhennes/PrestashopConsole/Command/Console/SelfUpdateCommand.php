<?php

/**
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
 *  @author    Hennes Hervé <contact@h-hennes.fr>
 *  @copyright 2013-2016 Hennes Hervé
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  http://www.h-hennes.fr/blog/
 */

namespace Hhennes\PrestashopConsole\Command\Console; //REnseigner Namespace

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Humbug\SelfUpdate\Updater;

/**
 * Commande qui permet de mettre à jour la console
 *
 */
class SelfUpdateCommand extends Command
{

    const PHAR_URL = 'https://github.com/nenes25/prestashop_console/raw/master/bin/prestashopConsole.phar';
    const VERSION_URL = 'https://github.com/nenes25/prestashop_console/raw/master/bin/phar/current.version';

    protected function configure()
    {
        $this
                ->setName('console:self-upgrade')
                ->setDescription('Upgrade console to last version (phar only)');
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {

        if ($this->getApplication()->getRunAs() == 'php') {
            $output->writeln('<error>This commande can only be run in phar mode</error>');
            return;
        }

        $updater = new Updater(null, false);
        $updater->getStrategy()->setPharUrl(self::PHAR_URL);
        $updater->getStrategy()->setVersionUrl(self::VERSION_URL);

        try {
            $result = $updater->update();
            if ($result) {
                $output->writeLn('<info>Prestashop console was updated with success to last version</info>');
            } else {
                $output->writeLn('<info>No update needed<info>');
            }
        } catch (\Exception $e) {
            $output->writeln('<error>Unable to update console</error>');
        }
    }

}
