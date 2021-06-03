<?php

use PrestashopConsole\Command\Configuration\GetCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class GetCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    /** @var string config key */
    const TEST_CONFIG_KEY = 'PHPUNIT_TEST_DELETE_COMMAND';

    protected function setUp(): void
    {
        Configuration::updateValue(self::TEST_CONFIG_KEY, 1);
        $this->commandTester = new CommandTester(
            new GetCommand()
        );
        parent::setUp();
    }

    public function testExecute(): void
    {
        //Check command status code
        $this->assertEquals(
            0,
            $this->commandTester->execute([
                'name' => self::TEST_CONFIG_KEY
            ])
        );

        //Check that the return of the display is equal to the config value
        $this->assertEquals(1, trim($this->commandTester->getDisplay()));
    }
}
