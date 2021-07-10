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

namespace PrestashopConsole\Command\Images;

use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ExportCommand extends Command
{
    /** @var array Images Types */
    protected $_types = [
        'all',
        'admin',
        'product',
        'category',
        'cms',
        'tmp',
    ];

    /** @var array Archives Types */
    protected $_archivesFormat = [
        'tar.gz',
        'zip',
    ];

    /** @var string */
    protected $_type;
    /** @var string */
    protected $_archiveFormat;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('images:export')
            ->setDescription('Export images')
            ->addOption('type', 't', InputOption::VALUE_OPTIONAL, 'type of file to export')
            ->addOption('interactive', 'i', InputOption::VALUE_OPTIONAL, 'Interactive Mode')
            ->addOption('archive', 'a', InputOption::VALUE_OPTIONAL, 'Archive format', 'tar.gz');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Shell_exec function is required
        //@Todo make it optionnal and do it also with php ( symfony finder )
        if (!function_exists('shell_exec')) {
            $output->writeln('<error>The function shell_exec is not present</error>');

            return self::RESPONSE_ERROR;
        }

        $this->_archiveFormat = $input->getOption('archive');
        if (!in_array($this->_archiveFormat, $this->_archivesFormat)) {
            $this->_archiveFormat = 'tar.gz';
        }
        $this->_type = $input->getOption('type');

        if ($input->getOption('interactive')) {
            $questionHelper = $this->getHelper('question');
            $imagesTypes = $this->_types;
            $typeQuestion = new Question('<question>Image Type :</question>');
            $typeQuestion->setAutocompleterValues($imagesTypes);
            $typeQuestion->setValidator(function ($answer) use ($imagesTypes) {
                if ($answer !== null && !in_array($answer, $imagesTypes)) {
                    throw new RuntimeException('The field type must be part of the suggested');
                }

                return $answer;
            });

            $this->_type = $questionHelper->ask($input, $output, $typeQuestion);
        }

        $this->_exportImages($output);

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Export images
     *
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function _exportImages(OutputInterface $output): void
    {
        switch ($this->_type) {
            case 'admin':
            case 'cms':
            case 'tmp':
                $directory = $this->_type;
                break;

            case 'product':
                $directory = 'p';
                break;

            case 'category':
                $directory = 'c';
                break;

            default:
                $directory = '';
                break;
        }

        $exportPath = _PS_IMG_DIR_ . $directory;

        if (is_dir($exportPath)) {
            $filePath = _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . date('YmdHi') . '-export-images' . $this->_type . '.' . $this->_archiveFormat;

            if ($this->_archiveFormat == 'tar.gz') {
                $command = 'tar czf ' . $filePath . ' *';
            } else {
                $command = 'zip -qr ' . $filePath . ' *';
            }

            $output->writeln('<info>Images export started</info>');
            $export = shell_exec('cd ' . $exportPath . ' && ' . $command);
            $output->writeln($export);
            $output->writeln('<info>Images export ended in path ' . $filePath . '</info>');
        } else {
            $output->writeln('<error>The path ' . $exportPath . ' does not exists</error>');
        }
    }
}
