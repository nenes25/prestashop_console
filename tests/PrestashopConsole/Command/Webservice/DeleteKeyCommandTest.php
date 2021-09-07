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


use PrestashopConsole\Command\Webservice\DeleteKeyCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class DeleteKeyCommandTest extends TestCase
{

    /** @var string Code of api key to create and delete */
    const TEST_KEY = 'LQSOMALWDVETCMCDEDFKNJCPTQJPPUVV';

    /** @var int identifier of existing webservice key */
    private $existingKeyId;

    /** @var CommandTester */
    private $commandTester;

    protected function setUp(): void
    {
        $this->commandTester = new CommandTester(
            new DeleteKeyCommand()
        );
        $webserviceKey = new WebserviceKey();
        $webserviceKey->key = self::TEST_KEY;
        $webserviceKey->save();
        $this->existingKeyId = $webserviceKey->id;
        parent::setUp();
    }

    /**
     * @dataProvider getCases
     */
    public function testExecute($data): void
    {
        //Check command status code
        $this->assertEquals(
            $data['response_code'],
            $this->commandTester->execute(
                $data['params']
            )
        );

        //Check command message
        $this->assertEquals(
            $data['response'],
            trim($this->commandTester->getDisplay())
        );
    }

    /**
     * @return Generator
     */
    public function getCases(): Generator
    {
        yield 'Case invalid key' => [
            [
                'params' => [
                    "key" => 'invalid_key',
                ],
                'response_code' => 1,
                'response' => 'The api key is invalid ( 32 characters required)',
            ]
        ];
        yield 'Case not existing key' => [
            [
                'params' => [
                    "key" => 'LQSOMALWDVETCMCDEDFKNJCPTQJPPUPD',
                ],
                'response_code' => 1,
                'response' => 'The api key does not exists',
            ]
        ];
        yield 'Case delete key success' => [
            [
                'params' => [
                    "key" => self::TEST_KEY,
                ],
                'response_code' => 0,
                'response' => 'Webservice key deleted with success',
            ]
        ];

    }
}
