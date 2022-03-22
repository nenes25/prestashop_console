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


use PrestashopConsole\Command\Customer\CreateCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use PHPUnit\Framework\TestCase;

class CreateCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    protected function setUp(): void
    {
        $command = new CreateCommand();
        $command->setHelperSet(new HelperSet([new QuestionHelper()]));
        $this->commandTester = new CommandTester(
            $command
        );
        parent::setUp();
    }

    /**
     * @param array $datas
     * @dataProvider getCases
     */
    public function testExecute($datas): void
    {

        //Check command status code
        $this->assertEquals(
            $datas['response_code'],
            $this->commandTester->execute(
                $datas['params']
            )
        );

        //Check command message
        $this->assertEquals(
            $datas['response_message'],
            trim($this->commandTester->getDisplay())
        );
    }


    public function getCases(): Generator
    {
        $emailOkRandomString = sprintf('unittestuser-frontend-%s@yopmail.com', time() * mt_rand(1, 1000));
        yield 'Case ok' => [
            [
                'params' => [
                    "--email" => $emailOkRandomString,
                    "--password" => 'prestashop123',
                    "--firstname" => 'frontend',
                    "--lastname" => 'user',
                ],
                'response_message' => sprintf('new customer %s created with success', $emailOkRandomString),
                'response_code' => 0,
            ]
        ];
    }
}