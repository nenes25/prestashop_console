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

use PrestashopConsole\Command\Configuration\SetCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class SetCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    /** @var string config key */
    const TEST_CONFIG_KEY = 'PHPUNIT_TEST_DELETE_COMMAND';

    protected function setUp(): void
    {
        $this->commandTester = new CommandTester(
            new SetCommand()
        );
        parent::setUp();
    }

    public function testExecute(): void
    {
        //Check command status code
        $this->assertEquals(
            0,
            $this->commandTester->execute([
                'name' => self::TEST_CONFIG_KEY,
                'value' => 1
            ])
        );

        //Check that the config is equal to the defined value
        $this->assertEquals(1, Configuration::get(self::TEST_CONFIG_KEY));
    }

    protected function tearDown():void
    {
        Configuration::deleteByName(self::TEST_CONFIG_KEY);
        parent::tearDown();
    }
}