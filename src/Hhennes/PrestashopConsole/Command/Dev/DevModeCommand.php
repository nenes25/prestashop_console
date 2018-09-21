<?php
/**
 * 2018-2018 Monterisi Sébastien
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
 * @author    Monterisi Sébastien <contact@seb7.fr>
 * @copyright 2007-2018 Hennes Hervé
 * @copyright 2007-2018 Monterisi Sébastien
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

namespace Hhennes\PrestashopConsole\Command\Dev;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to change prestashop debug mode
 *
 */
class DevModeCommand extends Command
{
    /**
     * @var array possible allowed dev mode passed in command
     */
    private $allowed_command_states = ['enable', 'disable', 'toggle'];

    private $enable_code  = '<?php define(\'_PS_MODE_DEV_\', true);';
    private $disable_code = '<?php define(\'_PS_MODE_DEV_\', false);';
    private $debug_file_name = 'debug_mode.php';

    protected function configure()
    {
        $this
            ->setName('dev:mode')
            ->setDescription('Enable / Disable debug mode (to display errors).')
            ->addArgument(
                        'state', InputArgument::REQUIRED, 'enable or disable debug mode ( possible values : '.implode(",", $this->allowed_command_states).') '.PHP_EOL
                .$this->getWarningText()
            );
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            // filter requested mode
            if(!in_array($input->getArgument('state'), $this->allowed_command_states)) {
                throw new \InvalidArgumentException("Unexpected state [".$input->getArgument('state')."] . Use one of : ".implode(",", $this->allowed_command_states));
            }

            // _PS_MODE_DEV_ not defined ?
            if(!defined('_PS_MODE_DEV_')) {
                throw new \RuntimeException(" constant _PS_MODE_DEV_ is not defined (?)");
            }

            // handle requested state
            $is_currently_enable = _PS_MODE_DEV_ ;
            $requested_state = $input->getArgument('state');
            if($input->getArgument('state') === 'toggle') {
                $requested_state = $is_currently_enable ? 'disable' : 'enable';
            }

            // no need to change mode
            if( (_PS_MODE_DEV_ && $requested_state === 'enable')  || (!_PS_MODE_DEV_ && $requested_state === 'disable')) {
                $output->writeln('<comment>Already in requested mode (_PS_MODE_DEV_ = '.(_PS_MODE_DEV_ ? 'true' : 'false').')</comment>');
                $output->writeln("<comment>{$this->getWarningText()}</comment>");
                return;
            }

            // create custom file if needed
            if(!file_exists($this->getDebugFilePath())) {
                if(!touch($this->getDebugFilePath())) {
                    throw new \RuntimeException("Failed to create ".$this->getDebugFilePath());
                }
            }

            // open & modify config file
            $file_contents = $requested_state === 'enable' ? $this->enable_code : $this->disable_code;

            if(!file_put_contents($this->getDebugFilePath(), $file_contents) ) {
                throw new \RuntimeException("Failed to write debug file. ");
            };

            $output->writeln("<info>Debug mode successfully changed (_PS_MODE_DEV_ = ".($requested_state === 'enable' ? 'true' : 'false').")</info>");
            $output->writeln("<comment>{$this->getWarningText()}</comment>");

        } catch (\Exception $e) {
            $output->writeln("<info>ERROR:" . $e->getMessage() . "</info>");

        }

    }

    private final function getWarningText() : string
    {
        return 'Be sure to include "include(__DIR__. \'/'.$this->debug_file_name.'\');" in config/defines.inc.php for this feature to run.';
    }

    /**
     * @return string
     */
    protected function getDebugFilePath(): string
    {
        return getcwd() . '/config/' . $this->debug_file_name;
    }
}
