<?php
/**
 * 2007-2019 Hennes Hervé
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
 * @copyright 2007-2019 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole\Command\Dev\Clean;

use Module;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CleanCommandAbstract extends Command
{

    /** @var string Prestashop clean Module */
    protected $_cleanModuleName = 'pscleaner';

    /** @var PSCleaner | null */
    protected $_cleanModuleInstance = null;

    /**
     * @inheritDoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if ($module = Module::getInstanceByName($this->_cleanModuleName)) {
            if (!Module::isInstalled($module->name) || !$module->active) {
                $output->writeln('<error>' . $this->_cleanModuleName . ' is not active or installed</error>');
                return self::RESPONSE_SUCCESS;;
            }
            $this->_cleanModuleInstance = $module;
        } else {
            $output->writeln('<error>' . $this->_cleanModuleName . ' is not installed</error>');
        }
    }
}
