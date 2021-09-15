<?php

use PrestashopConsole\Command\Analyze\ListCarriersCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class ListCarriersCommandTest extends TestCase
{
    /**
     * @var CommandTester
     */
    private $commandTester;

    protected function setUp(): void
    {
        $this->commandTester = new CommandTester(
            new ListCarriersCommand()
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