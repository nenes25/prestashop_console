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


use PrestashopConsole\Command\Configuration\GetCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class GetCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    /** @var string config key */
    const TEST_CONFIG_KEY = 'PHPUNIT_TEST_DELETE_COMMAND';

    protected function setUp(): void
    {
        Configuration::updateValue(self::TEST_CONFIG_KEY, 1);
        $this->commandTester = new CommandTester(
            new GetCommand()
        );
        parent::setUp();
    }

    public function testExecute(): void
    {
        //Check command status code
        $this->assertEquals(
            0,
            $this->commandTester->execute([
                'name' => self::TEST_CONFIG_KEY
            ])
        );

        //Check that the return of the display is equal to the config value
        $this->assertEquals(1, trim($this->commandTester->getDisplay()));
    }
}
