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

namespace PrestashopConsole\Command\Preferences;

use CMS;
use Context;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Shop;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This commands allow to enable/disable cms pages
 */
class CmsCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('preferences:cmspage')
            ->setDescription('Disable or enable a specific cms page')
            ->addArgument(
                'id',
                InputArgument::REQUIRED,
                'cms page id'
            )
            ->addArgument(
                'action',
                InputArgument::OPTIONAL,
                'enable|disable(default)'
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id_cms = (int) $input->getArgument('id');
        $action = $input->getArgument('action');

        Context::getContext()->shop->setContext(Shop::CONTEXT_ALL);

        $cms = new CMS($id_cms);

        if ($cms->id == null) {
            $output->writeln(sprintf("<error>Error Cms page %d doesn't exists</error>", $id_cms));

            return self::RESPONSE_ERROR;
        }

        switch ($action) {
            case 'enable':
                $cms->active = true;
                $output->writeln(sprintf('<info>Enable cms page %d</info>', $id_cms));
                break;
            case 'disable':
            default:
                $output->writeln(sprintf('<info>Disable cms page %d</info>', $id_cms));
                $cms->active = false;
                break;
        }

        try {
            $cms->save();
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            return self::RESPONSE_ERROR;
        }

        return self::RESPONSE_SUCCESS;
    }
}
