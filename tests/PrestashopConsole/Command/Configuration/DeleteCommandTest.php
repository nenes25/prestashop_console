<?php

use PrestashopConsole\Command\Configuration\DeleteCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class DeleteCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    /** @var string config key */
    const TEST_CONFIG_KEY = 'PHPUNIT_TEST_DELETE_COMMAND';

    protected function setUp(): void
    {
        Configuration::updateValue(self::TEST_CONFIG_KEY, 1);
        $this->commandTester = new CommandTester(
            new DeleteCommand()
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

        //Check that the config does not exists anymore
        $this->assertFalse(Configuration::get(self::TEST_CONFIG_KEY));
    }
}
