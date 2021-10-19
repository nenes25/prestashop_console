<?php
/**
 * 2007-2021 Hennes Hervé
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
 * @copyright 2007-2021 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * https://github.com/nenes25/prestashop_console*
 * https://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole\Command\Preferences;

use Configuration;
use Context;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Shop;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MaintenanceCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('preferences:maintenance')
            ->setDescription('Disable or enable shop')
            ->addArgument(
                'type',
                InputArgument::OPTIONAL,
                'enable|disable(default)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = $input->getArgument('type');
        Context::getContext()->shop->setContext(Shop::CONTEXT_ALL);

        switch ($type) {
            case 'enable':
                $output->writeln('<info>Shop is enabled</info>');
                Configuration::updateValue('PS_SHOP_ENABLE', 1);
                break;
            case 'disable':
            default:
                $output->writeln('<info>Shop is disabled</info>');
                Configuration::updateValue('PS_SHOP_ENABLE', 0);
                break;
        }

        return self::RESPONSE_SUCCESS;
    }
}
