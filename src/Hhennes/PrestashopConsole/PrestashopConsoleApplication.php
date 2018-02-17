<?php
/**
 * 2007-2018 Hennes Hervé
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
 * @copyright 2007-2018 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */
namespace Hhennes\PrestashopConsole;

use Symfony\Component\Console\Application as BaseApplication;

class PrestashopConsoleApplication extends BaseApplication
{

    const APP_NAME = 'prestashopConsole';

    const APP_VERSION = '0.1.0';

    /** @var string php|phar Console run mod */
    protected $_runAs = 'php';

    /**
     * Set RunAs Mode
     * @param type $mode
     */
    public function setRunAs($mode)
    {
        $this->_runAs = $mode;
    }

    /**
     * Get RunAs
     * @return type
     */
    public function getRunAs()
    {
        return $this->_runAs;
    }

}
