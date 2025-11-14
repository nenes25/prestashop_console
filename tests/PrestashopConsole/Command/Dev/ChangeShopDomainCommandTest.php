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

use PrestashopConsole\Command\Dev\ChangeShopDomainCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class ChangeShopDomainCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    /** @var Application */
    private $application;

    protected function setUp(): void
    {
        $this->application = new Application();
        $this->application->add(new ChangeShopDomainCommand());
        
        $command = $this->application->find('dev:change-domain');
        $this->commandTester = new CommandTester($command);
        
        parent::setUp();
    }

    public function testCommandIsConfigured(): void
    {
        $command = $this->application->find('dev:change-domain');
        
        $this->assertEquals('dev:change-domain', $command->getName());
        $this->assertEquals('Change the domain of the website', $command->getDescription());
    }

    public function testCommandHasCorrectStructure(): void
    {
        $command = $this->application->find('dev:change-domain');
        
        // Verify the command is properly configured
        $this->assertInstanceOf(ChangeShopDomainCommand::class, $command);
        $this->assertNotEmpty($command->getName());
        $this->assertNotEmpty($command->getDescription());
    }

    public function testCommandHasDomainArgument(): void
    {
        $command = $this->application->find('dev:change-domain');
        $definition = $command->getDefinition();
        
        $this->assertTrue($definition->hasArgument('domain'));
        $argument = $definition->getArgument('domain');
        $this->assertTrue($argument->isRequired());
        $this->assertEquals('New shop domain', $argument->getDescription());
    }

    public function testCommandHasPhysicalUriOption(): void
    {
        $command = $this->application->find('dev:change-domain');
        $definition = $command->getDefinition();
        
        $this->assertTrue($definition->hasOption('physical_uri'));
        $option = $definition->getOption('physical_uri');
        $this->assertEquals('Physical uri', $option->getDescription());
    }

    public function testCommandHasVirtualUriOption(): void
    {
        $command = $this->application->find('dev:change-domain');
        $definition = $command->getDefinition();
        
        $this->assertTrue($definition->hasOption('virtual_uri'));
        $option = $definition->getOption('virtual_uri');
        $this->assertEquals('Virtual uri', $option->getDescription());
    }

    public function testCommandHasIdShopOption(): void
    {
        $command = $this->application->find('dev:change-domain');
        $definition = $command->getDefinition();
        
        $this->assertTrue($definition->hasOption('id_shop'));
        $option = $definition->getOption('id_shop');
        $this->assertEquals('affected id_shop', $option->getDescription());
    }
}
