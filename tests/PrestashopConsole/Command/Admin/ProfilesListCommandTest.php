<?php

use PrestashopConsole\Command\Admin\ProfilesListCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class ProfilesListCommandTest extends TestCase
{
    /**
     * @var CommandTester
     */
    private $commandTester;

    protected function setUp(): void
    {
        $this->commandTester = new CommandTester(
            new ProfilesListCommand()
        );
        parent::setUp();
    }

    public function testExecute(): void
    {
        $this->assertEquals(
            0,
            $this->commandTester->execute([])
        );
    }

}