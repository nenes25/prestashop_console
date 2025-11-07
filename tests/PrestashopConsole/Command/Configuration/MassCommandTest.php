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
 *
 */

use PrestashopConsole\Command\Configuration\MassCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class MassCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    protected function setUp(): void
    {
        $command = new MassCommand();
        $this->commandTester = new CommandTester($command);
    }

    public function testExecuteWithNonExistingFile(): void
    {
        $result = $this->commandTester->execute([
            '--config' => '/tmp/nonexistent.yml'
        ]);
        
        $this->assertEquals(1, $result);
        $this->assertStringContainsString('doesnt exist', $this->commandTester->getDisplay());
    }

    public function testExecuteWithValidFile(): void
    {
        $yamlContent = <<<YAML
Configuration:
  updateValue:
    - key: TEST_CONFIG
      value: test_value
YAML;
        $tempFile = tempnam(sys_get_temp_dir(), 'test_mass_config_') . '.yml';
        file_put_contents($tempFile, $yamlContent);

        try {
            $result = $this->commandTester->execute([
                '--config' => $tempFile
            ]);
            
            $this->assertEquals(0, $result);
            $this->assertStringContainsString('processed successfully', $this->commandTester->getDisplay());
        } finally {
            unlink($tempFile);
        }
    }
}
