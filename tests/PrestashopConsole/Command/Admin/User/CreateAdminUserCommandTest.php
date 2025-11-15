<?php
/**
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
 * @copyright since 2016 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * https://github.com/nenes25/prestashop_console
 * https://www.h-hennes.fr/blog/
 */

use PrestashopConsole\Command\Admin\User\CreateCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use PHPUnit\Framework\TestCase;

class CreateAdminUserCommandTest extends TestCase
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
        if (isset($datas['expect_exception'])) {
            $this->expectException($datas['expect_exception']);
            $this->expectExceptionMessage($datas['response_message']);
            $this->commandTester->execute($datas['params']);
            return;
        }

        // Check command status code
        $this->assertEquals(
            $datas['response_code'],
            $this->commandTester->execute(
                $datas['params']
            )
        );

        // Check command message contains expected output
        $display = trim($this->commandTester->getDisplay());
        $this->assertStringContainsString(
            $datas['response_message'],
            $display
        );
    }

    public function getCases(): Generator
    {
        $emailOkRandomString = sprintf('unittestadmin-%s@yopmail.com', time() * mt_rand(1, 1000));
        
        yield 'Case ok - Valid admin creation' => [
            [
                'params' => [
                    '--email' => $emailOkRandomString,
                    '--password' => 'prestashop123',
                    '--firstname' => 'Admin',
                    '--lastname' => 'Test',
                ],
                'response_message' => 'Admin user created successfully!',
                'response_code' => 0,
            ]
        ];

        yield 'Case error - Invalid email' => [
            [
                'params' => [
                    '--email' => 'invalid-email',
                    '--password' => 'prestashop123',
                    '--firstname' => 'Admin',
                    '--lastname' => 'Test',
                ],
                'response_message' => 'The email is empty or not valid',
                'expect_exception' => RuntimeException::class,
            ]
        ];

        yield 'Case error - Empty firstname validated at final step' => [
            [
                'params' => [
                    '--email' => sprintf('admin-%s@yopmail.com', time() * mt_rand(1, 1000)),
                    '--password' => 'prestashop123',
                    '--firstname' => '',
                    '--lastname' => 'Test',
                ],
                'response_message' => 'Invalid firstname format',
                'response_code' => 1,
            ]
        ];

        yield 'Case error - Empty lastname validated at final step' => [
            [
                'params' => [
                    '--email' => sprintf('admin-%s@yopmail.com', time() * mt_rand(1, 1000)),
                    '--password' => 'prestashop123',
                    '--firstname' => 'Admin',
                    '--lastname' => '',
                ],
                'response_message' => 'Invalid lastname format',
                'response_code' => 1,
            ]
        ];
    }
}
