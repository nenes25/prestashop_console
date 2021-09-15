<?php

use PrestashopConsole\Command\Analyze\ListPaymentsCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class ListPaymentsCommandTest extends TestCase
{

    /**
     * @var CommandTester
     */
    private $commandTester;

    protected function setUp(): void
    {
        $this->commandTester = new CommandTester(
            new ListPaymentsCommand()
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