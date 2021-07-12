<?php

use PrestashopConsole\Command\Admin\User\ListCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class ListAdminUserCommandTest extends TestCase
{
    /**
     * @var CommandTester
     */
    private $commandTester;

    protected function setUp(): void
    {
        $this->commandTester = new CommandTester(
            new ListCommand()
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