This branch is showing you how to contribute to the projet.

If you want to add new functionnalities it's quite simple :

Create your new command in the path :
src/Hhennes/Prestashop/Command/PSFUNCTIONNALITY/SampleCommand.php
You can use the folowing code as an example:

<pre>

namespace Hhennes\PrestashopConsole\Command\; //Complete the path here

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Describe command action
 *
 * @author : Put your name here
 *
 * Status : In progress
 */
class ClearCommand extends Command
{

    // Configure your command
    protected function configure()
    {
    }

    //Execute your command
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }

}
</pre>

For example if your command deals with modules you can create it in :
src/Hhennes/Prestashop/Command/Module/SampleCommand.php

To make your command works you have to register it in the file config.php above the existing ones.
Simply by adding its namespace.

Then everything should works

