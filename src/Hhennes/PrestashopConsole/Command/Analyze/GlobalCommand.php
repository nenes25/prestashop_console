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

namespace Hhennes\PrestashopConsole\Command\Analyze;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class GlobalCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('analyze:global')
            ->setDescription('Run a global analysis on the website')
            ->setHelp(
                'This command is a "meta command" which run successively the following commands:'.PHP_EOL
                .'- analyze:website (website statistics)'.PHP_EOL
                .'- module:list --active (List all active modules)'.PHP_EOL
                .'- module:list --active --no-native (List all active and non prestashop modules)'.PHP_EOL
                .'- analyze:payments (List installed payments modules)'.PHP_EOL
                .'- analyze:carriers --active (List active carriers modules)'.PHP_EOL
                .'- dev:list-overrides (List overrides of the project)'.PHP_EOL
            );
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commands = $this->getCommandList();
        foreach ($commands as $command) {
            try {
                $output->writeln('<comment>========================</comment>');
                $output->writeln('<comment>' . $command['description'] . '</comment>');
                $runCommand = $this->getApplication()->find($command['name']);
                if (array_key_exists('arguments', $command)) {
                    $arguments = new ArrayInput($command['arguments']);
                    $runCommand->run($arguments, $output);
                } else {
                    $runCommand->run($input, $output);
                }
            } catch (\Exception $e) {
                $output->writeln(
                    sprintf(
                        '<error>Get exception during command run %s</error>',
                        $e->getMessage()
                    )
                );
                return 1;
            }
        }

        return 0;
    }

    /**
     * Get the list of commands to execute
     * @return array
     */
    protected function getCommandList()
    {
        $commands = [
            [
                'description' => 'Get website statistics',
                'name' => 'analyze:website',
            ],
            [
                'description' => 'List installed modules',
                'name' => 'module:list',
                'arguments' => [
                    '--active' => true,
                ]
            ],
            [
                'description' => 'List installed modules no natives',
                'name' => 'module:list',
                'arguments' => [
                    '--active' => true,
                    '--no-native' => true,
                ]
            ],
            [
                'description' => 'List installed payments modules',
                'name' => 'analyze:payments',
            ],
            [
                'description' => 'List installed carriers',
                'name' => 'analyze:carriers',
                'arguments' => [
                    '--active' => true,
                ]
            ],
            [
                'description' => 'List overrides of the project',
                'name' => 'dev:list-overrides',
            ],
        ];

        return $commands;
    }
}
