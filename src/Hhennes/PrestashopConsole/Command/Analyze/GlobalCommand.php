<?php
/**
 * Hervé HENNES
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file docs/licenses/LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@h-hennes.fr so we can send you a copy immediately.
 *
 * @author    Hervé HENNES <contact@h-hhennes.fr>
 * @copyright since 2021 Hervé HENNES
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License ("AFL") v. 3.0
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
            ->setDescription('Run a global analysis on the website');
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
