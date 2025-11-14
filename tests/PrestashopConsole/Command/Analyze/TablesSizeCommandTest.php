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
 * https://github.com/nenes25/prestashop_console
 * https://www.h-hennes.fr/blog/
 */

use PrestashopConsole\Command\Analyze\TablesSizeCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class TablesSizeCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    /** @var Application */
    private $application;

    protected function setUp(): void
    {
        $this->application = new Application();
        $this->application->add(new TablesSizeCommand());
        
        $command = $this->application->find('analyze:tables:size');
        $this->commandTester = new CommandTester($command);
        
        parent::setUp();
    }

    public function testCommandIsConfigured(): void
    {
        $command = $this->application->find('analyze:tables:size');
        
        $this->assertEquals('analyze:tables:size', $command->getName());
        $this->assertEquals('Analyze the size of the database tables sorted by size', $command->getDescription());
    }

    public function testCommandHasCorrectStructure(): void
    {
        $command = $this->application->find('analyze:tables:size');
        
        // Verify the command is properly configured
        $this->assertInstanceOf(TablesSizeCommand::class, $command);
        $this->assertNotEmpty($command->getName());
        $this->assertNotEmpty($command->getDescription());
    }

    public function testCommandHasMinRowsOption(): void
    {
        $command = $this->application->find('analyze:tables:size');
        $definition = $command->getDefinition();
        
        $this->assertTrue($definition->hasOption('min-rows'));
        $option = $definition->getOption('min-rows');
        $this->assertEquals('Show only tables with a minimum number of rows', $option->getDescription());
    }

    public function testCommandHasOutputFormatOption(): void
    {
        $command = $this->application->find('analyze:tables:size');
        $definition = $command->getDefinition();
        
        $this->assertTrue($definition->hasOption('output'));
        $option = $definition->getOption('output');
        $this->assertEquals('Output format (table or json)', $option->getDescription());
    }
}
