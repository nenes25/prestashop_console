<?php

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
