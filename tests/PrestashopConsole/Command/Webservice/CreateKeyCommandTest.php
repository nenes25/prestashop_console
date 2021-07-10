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


use PrestashopConsole\Command\Webservice\CreateKeyCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class CreateKeyCommandTest extends TestCase
{

    /** @var string Code of api key to create */
    const TEST_KEY = 'LQSOMALWDVETCMCDEDFKNJCPTQJPPUVV';

    /** @var string Code of api key for test which exists */
    const EXISTING_KEY = 'LQSOMALWDVETCMCDEDFKNJCPTQJPPUTT';

    /** @var int identifier of existing webservice key */
    private $existingKeyId;

    /** @var CommandTester */
    private $commandTester;

    protected function setUp(): void
    {
        $this->commandTester = new CommandTester(
            new CreateKeyCommand()
        );
        $webserviceKey = new WebserviceKey();
        $webserviceKey->key = self::EXISTING_KEY;
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

    public function getCases(): Generator
    {
        yield 'Case ok' => [
            [
                'params' => [
                    "--key" => self::TEST_KEY,
                ],
                'response_code' => 0,
                'response' => self::TEST_KEY,
            ]
        ];
        yield 'Case invalid key' => [
            [
                'params' => [
                    "--key" => 'invalid_key',
                ],
                'response_code' => 1,
                'response' => 'The api key is invalid ( 32 characters required)',
            ]
        ];
        yield 'Existing key' => [
            [
                'params' => [
                    "--key" => self::EXISTING_KEY,
                ],
                'response_code' => 1,
                'response' => 'The api key already exists',
            ]
        ];
    }

    protected function tearDown(): void
    {
        //Delete create webservice(s) key(s) created with this test(s)
        $idKey = Db::getInstance()->getValue("
                SELECT id_webservice_account 
                FROM " . _DB_PREFIX_ . "webservice_account
                WHERE `key`= '" . pSQL(self::TEST_KEY) . "' 
        ");

        $webserviceKey = new WebserviceKey($idKey);
        $webserviceKey->delete();

        if (null !== $this->existingKeyId) {
            $webserviceKey2 = new WebserviceKey($this->existingKeyId);
            $webserviceKey2->delete();
        }

        parent::tearDown();
    }
}
