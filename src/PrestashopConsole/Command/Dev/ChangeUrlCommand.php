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
 *
 * https://github.com/nenes25/prestashop_console/
 * http://www.h-hennes.fr/blog/
 */


namespace PrestashopConsole\Command\Dev;

use Db;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Validate;

use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ChangeUrlCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('dev:change-url')
            ->setDescription('Anonymize Customer information')
            ->addArgument('url', InputArgument::REQUIRED, 'the url to change without http://')
            ->addOption('id_shop', null, InputOption::VALUE_OPTIONAL, 'id_shop to change')
            ->setHelp('This command will change the url in table shop_url, in a next version it will also detect and change the urls in contents');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = $input->getArgument('url');
        $idShop = $input->getOption('id_shop');
        $where = '';

        if (!$this->validateUrl($url)) {
            throw new RuntimeException("Url is invalid");
        }

        if (null !== $idShop) {
            if ($this->validateIdShop((int)$idShop)) {
                $where .= 'id_shop = ' . (int)$idShop;
            } else {
                throw new RuntimeException("Id shop is invalid or not exists");
            }
        }
        if ($where == '') {
            $nbRows = Db::getInstance()->executeS("SELECT id_shop FROM " . _DB_PREFIX_ . "shop_url");
            if (count($nbRows) > 1) {
                $questionHelper = $this->getHelper('question');
                $confirmQuestionAnswer = $questionHelper->ask(
                    $input,
                    $output,
                    $this->getConfirmMultipleUpdateQuestion()
                );
                if ($confirmQuestionAnswer !== 'yes') {
                    return Command::RESPONSE_ERROR;
                }
            }
        }
        try {
            Db::getInstance()->update(
                'shop_url',
                [
                    'domain' => $url,
                    'domain_ssl' => $url
                ]
            );
        } catch (\Exception $e) {
            $output->writeln('<error>An error occurs while updating urls</error>');
            return Command::RESPONSE_ERROR;
        }

        $output->writeln('<info>Url have been updated with success</info>');

        return Command::RESPONSE_SUCCESS;
    }

    /**
     * Check if new url is valid
     * @param string $url
     * @return bool
     */
    protected function validateUrl(string $url): bool
    {
        return Validate::isUrl($url);
    }

    /**
     * Check if id_shop is valid and exists
     * @param int $shopId
     * @return bool
     */
    protected function validateIdShop(int $shopId): bool
    {
        return
            Validate::isUnsignedId($shopId)
            && false !== Db::getInstance()
                ->getValue("SELECT id_shop FROM " . _DB_PREFIX_ . "shop_url WHERE id_shop =" . $shopId);
    }

    /**
     * Get the question to confirm the update if multiple id_shop will be updated
     * @return Question
     */
    protected function getConfirmMultipleUpdateQuestion(): Question
    {
        $question = new Question('<error>Multiple id_shop exists are you sure you want to replace all ? (yes/no)</error>');
        $question
            ->setValidator(function ($answer) {
                if ($answer !== 'yes') {
                    $answer = 'no';
                }
                return $answer;
            })
            ->setNormalizer(function ($answer) {
                return trim(strtolower($answer));
            });

        return $question;
    }
}
