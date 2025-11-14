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

use PrestashopConsole\Command\Analyze\GlobalCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class GlobalCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    /** @var Application */
    private $application;

    protected function setUp(): void
    {
        $this->application = new Application();
        $this->application->add(new GlobalCommand());
        
        $command = $this->application->find('analyze:global');
        $this->commandTester = new CommandTester($command);
        
        parent::setUp();
    }

    public function testCommandIsConfigured(): void
    {
        $command = $this->application->find('analyze:global');
        
        $this->assertEquals('analyze:global', $command->getName());
        $this->assertEquals('Run a global analysis on the website', $command->getDescription());
        $this->assertStringContainsString('meta command', $command->getHelp());
    }

    public function testCommandHasCorrectStructure(): void
    {
        $command = $this->application->find('analyze:global');
        
        // Verify the command is properly configured
        $this->assertInstanceOf(GlobalCommand::class, $command);
        $this->assertNotEmpty($command->getName());
        $this->assertNotEmpty($command->getDescription());
    }
}
