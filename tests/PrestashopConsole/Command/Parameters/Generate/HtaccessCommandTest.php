<?php


use PrestashopConsole\Command\Parameters\Generate\HtaccessCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class HtaccessCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    protected function setUp(): void
    {
        $this->commandTester = new CommandTester(
            new HtaccessCommand()
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
            '.htaccess file generated with success',
            trim($this->commandTester->getDisplay())
        );

        //Check that file exists
        $this->assertFileExists($this->getHtaccessFilePath());

    }

    /**
     * If the file already exists before the test we delete it
     */
    protected function deleteInitialFile(): void
    {
        if (is_file($this->getHtaccessFilePath())) {
            unlink($this->getHtaccessFilePath());
        }
    }

    protected function getHtaccessFilePath(): string
    {
        return _PS_ROOT_DIR_ . '/.htaccess';
    }
}
