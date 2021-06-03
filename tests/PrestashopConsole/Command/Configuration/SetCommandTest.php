<?php

use PrestashopConsole\Command\Configuration\SetCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class SetCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    /** @var string config key */
    const TEST_CONFIG_KEY = 'PHPUNIT_TEST_DELETE_COMMAND';

    protected function setUp(): void
    {
        $this->commandTester = new CommandTester(
            new SetCommand()
        );
        parent::setUp();
    }

    public function testExecute(): void
    {
        //Check command status code
        $this->assertEquals(
            0,
            $this->commandTester->execute([
                'name' => self::TEST_CONFIG_KEY,
                'value' => 1
            ])
        );

        //Check that the config is equal to the defined value
        $this->assertEquals(1, Configuration::get(self::TEST_CONFIG_KEY));
    }

    protected function tearDown():void
    {
        Configuration::deleteByName(self::TEST_CONFIG_KEY);
        parent::tearDown();
    }
}