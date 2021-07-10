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


use PrestashopConsole\Command\Parameters\Generate\RobotsTxtCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class RobotsTxtCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    protected function setUp(): void
    {
        $this->commandTester = new CommandTester(
            new RobotsTxtCommand()
        );
        $this->deleteInitialFile();
        parent::setUp();
    }

    public function testExecute(): void
    {
        //Check command status code
        $this->assertEquals(0, $this->commandTester->execute([]));

        //Check command message
        $this->assertEquals(
            'robots.txt file generated with success',
            trim($this->commandTester->getDisplay())
        );

        //Check that file exists
        $this->assertFileExists($this->getFilePath());

    }

    /**
     * If the file already exists before the test we delete it
     */
    protected function deleteInitialFile(): void
    {
        if (is_file($this->getFilePath())) {
            unlink($this->getFilePath());
        }
    }

    protected function getFilePath(): string
    {
        return _PS_ROOT_DIR_ . '/robots.txt';
    }

}
