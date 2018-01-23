<?php

/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    Hennes Hervé <contact@h-hennes.fr>
 *  @copyright 2013-2016 Hennes Hervé
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  http://www.h-hennes.fr/blog/
 */

namespace Hhennes\PrestashopConsole\Command\Dev\Clean;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


class CleanCommand extends CleanCommandAbstract {
    
    use \Symfony\Component\Console\Command\LockableTrait;
    
    protected $_allowedCleanType = ['all','catalog','sales'];

    protected function configure() {
        $this
                ->setName('dev:clean')
                ->setDescription('Clean existing datas with module PsCleaner')
                ->addArgument('type', InputArgument::REQUIRED, 'data types. Possibles values '. implode(', ', $this->_allowedCleanType));
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        
        if ( $this->_cleanModuleInstance ) {      
        
            $type = $input->getArgument('type');
            
            if (!$this->lock()) {
               $output->writeln('<error>The command is already running in another process.</error>');
                return 0;
            }
            
            switch ( $type ) {
                
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
                    break;
            }
        }
    }
}
