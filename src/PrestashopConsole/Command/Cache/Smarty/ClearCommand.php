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

namespace PrestashopConsole\Command\Cache\Smarty;

use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tools;

/**
 * Commande qui permet de supprimer tout le cache smarty
 * (Fichiers compilés + cache)
 * Pour l'instant nécessite la function exec
 *
 * @todo Optmimiser pour ne pas supprimer le fichier index.php + gérer le mode SQL
 */
class ClearCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
                ->setName('cache:smarty:clear')
                ->setDescription('Clear smarty cache');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Tools::clearSmartyCache();
        $output->writeln('<info>Smarty Cache and compiled dir cleaned</info>');

        return self::RESPONSE_SUCCESS;
    }
}
