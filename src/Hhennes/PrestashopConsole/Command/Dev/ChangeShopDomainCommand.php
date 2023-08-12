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


namespace Hhennes\PrestashopConsole\Command\Dev;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Db;
use Validate;

/**
 * Class ChangeUrl
 * This command will change the domain of the shop
 */
class ChangeShopDomainCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('dev:change-domain')
            ->setDescription('Change the domain of the website')
            ->addArgument('domain', InputArgument::REQUIRED, 'New shop domain')
            ->addOption('physical_uri', null, InputOption::VALUE_OPTIONAL, 'Physical uri')
            ->addOption('virtual_uri', null, InputOption::VALUE_OPTIONAL, 'Virtual uri')
            ->addOption('id_shop', null, InputOption::VALUE_OPTIONAL, 'affected id_shop')
            ->setHelp(
                'This command allow to change the domain , physical_uri and virtual_uri of registered shops'
            );
    }

    /**
     * @inheritDoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $domain = $input->getArgument('domain');
        $physicalUri = $input->getOption('physical_uri');
        $virtualUri = $input->getOption('virtual_uri');
        $id_shop = $input->getOption('id_shop');

        //Validate domain
        if (!$this->validateDomain($domain)) {
            $output->writeln('<error>The provided domain is invalid</error>');
            return 1;
        }

        //Validate options
        if (null !== $physicalUri && !$this->validateUri($physicalUri)) {
            $output->writeln('<error>The provided physical uri is invalid</error>');
            return 1;
        }
        if (null !== $virtualUri && !$this->validateUri($virtualUri)) {
            $output->writeln('<error>The provided virtual uri is invalid</error>');
            return 1;
        }
        if (null !== $id_shop && !Validate::isUnsignedId($id_shop)) {
            $output->writeln('<error>The provided id_shop is invalid</error>');
            return 1;
        }

        //Update domain
        try {
            $this->updateDomain(
                $domain,
                $physicalUri,
                $virtualUri,
                $id_shop
            );
            $output->writeln('<info>The domain have been updated with success</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>An error occurs when updating domain</error>');
            return 1;
        }

        return 0;
    }

    /**
     * Update domain
     * @param string $domain
     * @param ?string $physicalUri
     * @param ?string $virtualUri
     * @param ?int $id_shop
     * @return bool
     */
    protected function updateDomain($domain, $physicalUri = null, $virtualUri = null, $id_shop = null)
    {
        $updatedData = [
            'domain' => pSQL($domain),
            'domain_ssl' => pSQL($domain),
        ];
        if (null !== $physicalUri) {
            $updatedData['physical_uri'] = pSQL($this->normalizeUri($physicalUri));
        }
        if (null !== $virtualUri) {
            $updatedData['virtual_uri'] = pSQL($virtualUri);
        }

        return Db::getInstance()->update(
            'shop_url',
            $updatedData,
            null !== $id_shop ? 'id_shop=' . $id_shop : ''
        );
    }

    /**
     * Normalize uri with /
     * @param string $uri
     * @return string
     */
    protected function normalizeUri($uri)
    {
        if (strpos($uri, '/') !== 1) {
            $uri = '/'.$uri;
        }

        return $uri;
    }

    /**
     * Validate that the domain is well formatted
     * @param string $domain
     * @return bool
     */
    protected function validateDomain($domain)
    {
        return !preg_match('#^http?://#', $domain);
    }

    /**
     * Validate uri
     * @param string $uri
     * @return bool
     */
    protected function validateUri($uri)
    {
        return true;
        //return Validate::isDirName($uri);
    }
}
