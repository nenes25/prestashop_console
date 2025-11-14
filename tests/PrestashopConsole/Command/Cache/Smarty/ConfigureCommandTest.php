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

use PrestashopConsole\Command\Cache\Smarty\ConfigureCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class ConfigureCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    protected function setUp(): void
    {
        $command = new ConfigureCommand();
        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteWithValidConfig(): void
    {
        $result = $this->commandTester->execute([
            'name' => 'compile',
            'value' => '0'
        ]);
        
        $this->assertEquals(0, $result);
        $this->assertStringContainsString('Update configuration compile with 0', $this->commandTester->getDisplay());
    }

    public function testExecuteWithInvalidName(): void
    {
        $result = $this->commandTester->execute([
            'name' => 'invalid',
            'value' => 'test'
        ]);
        
        $this->assertEquals(1, $result);
        $this->assertStringContainsString('Name not allowed', $this->commandTester->getDisplay());
    }

    public function testExecuteWithInvalidValue(): void
    {
        $result = $this->commandTester->execute([
            'name' => 'compile',
            'value' => '99'
        ]);
        
        $this->assertEquals(1, $result);
        $this->assertStringContainsString('Value not allowed', $this->commandTester->getDisplay());
    }
}
