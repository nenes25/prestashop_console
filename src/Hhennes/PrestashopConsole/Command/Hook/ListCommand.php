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
 *
 * https://github.com/nenes25/prestashop_console
 * https://www.h-hennes.fr/blog/
 */


namespace Hhennes\PrestashopConsole\Command\Hook;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Hook;

/**
 * Class Module
 * List hook with registered modules
 */
class ListCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('hook:list')
            ->setDescription('List all hooks registered in database');
    }

    /**
     * @inheritDoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        //Get Hooks list
        $hooks = Hook::getHooks();

        //Extract only hooks name
        $hooks = array_map(function ($row) {
            return $row['name'];
        }, $hooks);

        //Sort hooks by name
        usort($hooks, array($this, "cmp"));

        //Init Table
        $table = new Table($output);
        $table->setHeaders(['Hook Name']);

        foreach ($hooks as $hook) {
            $table->addRow([$hook]);
        }

        //Display result
        $table->render();
    }

    /**
     * Function to sort hook by name
     * @param $a
     * @param $b
     * @return int|\lt
     */
    private function cmp($a, $b)
    {
        return strcmp($a, $b);
    }
}
