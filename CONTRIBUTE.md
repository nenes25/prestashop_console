If you want to add new functionnalities to the console it's quite simple :

## How to install it
Go the root directory of your prestashop

Clone the github repository in the directory console
 ```bash
git clone https://github.com/nenes25/prestashop_console.git console
 ```
Go into the directory and run composer install
 ```bash
cd console
composer install
 ```
Then the tool is installed, and you can run the console with
 ```bash
php console.php
 ```

Create your new command in the path :
src/Hhennes/Prestashop/Command/PSFUNCTIONNALITY/SampleCommand.php
You can use the following code as an example:

<pre>
/**
 * 2007-2020 Hennes Hervé
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
 * @author    Yourname
 * @copyright 2007-2020 Yourname
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * https://github.com/nenes25/prestashop_console
 */

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

Your command then will be find automatically and then you can work on it.

If you want to share your work please make a pull request on github :)

