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


use PrestashopConsole\Command\Admin\User\CreateCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;

class CreateAdminCommandTest extends TestCase
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
    }

    /**
     * @param array $datas
     * @dataProvider getCases
     */
    public function testExecute($datas): void
    {
        //Gestion des messages d'exception
        if ($datas['expect_exception'] == 1) {
            $this->expectException(
                \RuntimeException::class
            );
            //Check command status code
            $this->assertEquals(
                $datas['response_code'],
                $this->commandTester->execute(
                    $datas['params']
                )
            );
        } else {

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
    }


    public function getCases(): Generator
    {
        $emailOkRandomString = sprintf('unittestuser-%s@yopmail.com', time() * mt_rand(1, 1000));
        yield 'Case ok' => [
            [
                'params' => [
                    "--email" => $emailOkRandomString,
                    "--password" => 'prestashop123',
                    "--firstname" => 'admin',
                    "--lastname" => 'user',
                ],
                'expect_exception' => 0,
                'response_message' => sprintf('New user %s created', $emailOkRandomString),
                'response_code' => 0,
            ]
        ];

        yield 'Case employee exists' => [
            [
                'params' => [
                    "--email" => $emailOkRandomString,
                    "--password" => 'prestashop123',
                    "--firstname" => 'admin',
                    "--lastname" => 'user',
                ],
                'expect_exception' => 0,
                'response_message' => 'Employee with this email already exists',
                'response_code' => 1,
            ]
        ];

        /**
         * The following tests show that the initial command should better manage user inputs
         * Returns should be normalized in case of errors
         * Issue #217 is on open and once fixed we should update this tests too
         */

        yield 'Wrong email format' => [
            [
                'params' => [
                    "--email" => 'wrong@test',
                    "--password" => 'prestashop123',
                    "--firstname" => 'admin',
                    "--lastname" => 'user',
                ],
                'expect_exception' => 1,
                'response_message' => 'PrestaShopException: Erreur fatale',
                'response_code' => 1,
            ]
        ];

        /* Should raise an error but not
         * yield 'Wrong password length' => [
            [
                'params' => [
                    "--email" => sprintf('unittestuser-%s@yopmail.com', time() * mt_rand(1, 1000)),
                    "--password" => '1',
                    "--firstname" => 'admin',
                    "--lastname" => 'user',
                ],
                'response_message' => 'PrestaShopException: Erreur fatale',
                'response_code' => 1,
            ]
        ];*/

        yield 'Wrong firstname' => [
            [
                'params' => [
                    "--email" => sprintf('unittestuser-%s@yopmail.com', time() * mt_rand(1, 1000)),
                    "--password" => 'prestashop123',
                    "--firstname" => '1234',
                    "--lastname" => 'user',
                ],
                'expect_exception' => 1,
                'response_message' => 'La propriété Employee->firstname n\'est pas valide.',
                'response_code' => 1,
            ]
        ];

        yield 'Wrong lastname' => [
            [
                'params' => [
                    "--email" => sprintf('unittestuser-%s@yopmail.com', time() * mt_rand(1, 1000)),
                    "--password" => 'prestashop123',
                    "--firstname" => 'admin',
                    "--lastname" => '1234',
                ],
                'expect_exception' => 1,
                'response_message' => 'La propriété Employee->lastname n\'est pas valide.',
                'response_code' => 1,
            ]
        ];
    }
}
