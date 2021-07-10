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

namespace PrestashopConsole\Command\Console;

use Exception;
use Humbug\SelfUpdate\Updater;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet de mettre à jour la console
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
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->getApplication()->getRunAs() == 'php') {
            $output->writeln('<error>This commande can only be run in phar mode</error>');

            return self::RESPONSE_ERROR;
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
        } catch (Exception $e) {
            $output->writeln('<error>Unable to update console</error>');
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            return self::RESPONSE_ERROR;
        }

        return self::RESPONSE_SUCCESS;
    }
}
