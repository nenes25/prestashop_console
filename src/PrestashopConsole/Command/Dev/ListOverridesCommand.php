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

namespace PrestashopConsole\Command\Dev;

use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * This commands list all files other than index.php present in the "override" directory of prestashop
 */
class ListOverridesCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('dev:list-overrides')
            ->setDescription('List overrides of classes and controllers in the project');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $finder = new Finder();
            $table = new Table($output);
            $table->setHeaders(
                [
                    'type',
                    'file',
                ]
            );

            $finder->files()->in(_PS_OVERRIDE_DIR_)->name('*.php')->notName('index.php');
            if ($finder->count()) {
                foreach ($finder as $file) {
                    $pathName = $file->getRelativePathname();
                    $pathType = $this->getPathType($pathName);
                    $table->addRow([
                        $pathType,
                        $pathName,
                    ]);
                }
                $table->render();
            } else {
                $output->writeln('<info>No overrides in this project</info>');
            }
        } catch (\Exception $e) {
            $output->writeln('<info>ERROR:' . $e->getMessage() . '</info>');

            return self::RESPONSE_ERROR;
        }

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Get path type
     *
     * @param string $pathName
     *
     * @return string
     */
    protected function getPathType(string $pathName): string
    {
        if (preg_match('#^controllers#', $pathName)) {
            $type = 'controller';
        } elseif (preg_match('#^classes#', $pathName)) {
            $type = 'classes';
        } elseif (preg_match('#^modules#', $pathName)) {
            $type = 'modules';
        } else {
            $type = 'Unknown';
        }

        return $type;
    }
}
