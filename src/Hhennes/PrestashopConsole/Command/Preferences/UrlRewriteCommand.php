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

namespace Hhennes\PrestashopConsole\Command\Preferences;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commande qui permet d'activer / desactiver la réécriture d'url
 *
 */
class UrlRewriteCommand extends Command
{
     protected function configure()
    {
        $this
            ->setName('preferences:urlrewrite')
            ->setDescription('Disable or enable Url Rewrite')
            ->addArgument(
                'type', InputArgument::OPTIONAL, 'enable|disable(default)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');

        \Context::getContext()->shop->setContext(\Shop::CONTEXT_ALL);

        switch ($type) {
            case 'enable':
                $output->writeln("<info>Url rewrite is enabled</info>");
                \Configuration::updateValue('PS_REWRITING_SETTINGS', 1);
                break;
            case 'disable':
            default:
                $output->writeln("<info>Url rewrite is disabled</info>");
                \Configuration::updateValue('PS_REWRITING_SETTINGS', 0);
                break;
        }
    }
}
