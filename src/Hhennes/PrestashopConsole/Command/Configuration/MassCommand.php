<?php

/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE successfully source file is subject to the Academic Free License (AFL 3.0)
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
 * @author    Mariusz Mielnik <mariusz@ecbox.pl>
 * @copyright 2013-2016 Mariusz Mielnik
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  http://www.ecbox.pl
 */
namespace Hhennes\PrestashopConsole\Command\Configuration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Commands: Mass configuration operations based on yaml definition
 *
 */
class MassCommand extends Command
{

    protected $allowedCalls = [
        'Configuration' => [
            'updateValue',
            'deleteByName',
            'updateGlobalValue',
            'set'
        ]];


    protected function configure()
    {
        $this
            ->setName('configuration:mass')
            ->setDescription('Mass operation configured in yaml file')
            ->addOption('config', null, InputOption::VALUE_REQUIRED, 'Yaml definition file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $yamlFile = $input->getOption('config');

        //check if file exist
        if (file_exists($yamlFile)) {
            //parse yaml file
            $definitions = Yaml::parse(file_get_contents($yamlFile));
            //Get the call object name;
            $callObjName = key($definitions);

            //check if object is allowed to call
            if (in_array($callObjName, array_keys($this->allowedCalls))) {
                //create instance
                $callObject = new $callObjName();

                foreach ($definitions[$callObjName] as $method => $params) {

                    //check if method of object is allowed to call
                    if (in_array($method, array_values($this->allowedCalls[$callObjName]))) {

                        //check if configured method exist
                        if (method_exists($callObject, $method)) {

                            //if single params for one method convert to indexed array
                            if (isset($params['key'])) {
                                $params = array($params);
                            }

                            //call the same method with different params
                            foreach ($params as $callParams) {
                                $firstValue = reset($callParams);
                                $firstKey = key($callParams);
                                $output->writeln("<comment>Calling $callObjName.$method($firstKey => $firstValue [...])</comment>");
                                call_user_func_array(array($callObject, $method), array_values($callParams));
                            }

                        } else {
                            $output->writeln("<error>Method '$callObjName.$method' doesnt exist</error>");
                        }
                    } else {
                        $output->writeln("<error>Method '$callObjName.$method' is not allowed</error>");
                    }
                }
            } else {
                $output->writeln("<error>Object '$callObjName' is not allowed</error>");
            }
        } else {
            $output->writeln("<error>Yaml definition file: '$yamlFile' doesnt exist!</error>");
            return;
        }

        $output->writeln("<info>Definitions from file '$yamlFile' processed successfully!</info>");
    }

}