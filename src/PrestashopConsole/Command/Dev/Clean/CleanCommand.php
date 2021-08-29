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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\Factory;
use Symfony\Component\Lock\Store\SemaphoreStore;

class CleanCommand extends CleanCommandAbstract
{
    /**
     * @var string[]
     */
    protected $_allowedCleanType = ['all', 'catalog', 'sales'];

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
                ->setName('dev:clean')
                ->setDescription('Clean existing datas with module PsCleaner')
                ->addArgument('type', InputArgument::REQUIRED, 'data types. Possibles values ' . implode(', ', $this->_allowedCleanType));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->_cleanModuleInstance) {
            $type = $input->getArgument('type');

            $store = new SemaphoreStore();
            $factory = new Factory($store);

            $lock = $factory->createLock($this->getName());
            if (!$lock->acquire()) {
                $output->writeln('<error>The command is already running in another process.</error>');

                return self::RESPONSE_ERROR;
            }

            switch ($type) {
                case 'all':
                    $this->_cleanModuleInstance->truncate('catalog');
                    $this->_cleanModuleInstance->truncate('sales');
                    $output->writeln('<info>All datas have been cleaned</info>');
                    break;
                case 'catalog':
                    $this->_cleanModuleInstance->truncate('catalog');
                    $output->writeln('<info>Catalog datas have been cleaned</info>');
                    break;
                case 'sales':
                    $this->_cleanModuleInstance->truncate('sales');
                    $output->writeln('<info>Sales datas have been cleaned</info>');
                    break;
                default:
                    $output->writeln('<error>Unknow clean type</error>');

                    return self::RESPONSE_ERROR;
                    break;
            }
        }

        return self::RESPONSE_SUCCESS;
    }
}
