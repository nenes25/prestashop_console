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

namespace PrestashopConsole\Command\Install;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * This commands allow to enable/disable cms categories
 *
 */
class InstallCommand extends Command
{
    // Format with the full version
    const DOWNLOAD_URL_PATTERN =  "https://www.prestashop.com/download/old/prestashop_%s.zip";

    /** @var array commands options */
    protected $_options = array(
        'psVersion',
        'domainName',
        'dbname',
        'dbuser',
        'dbpassword',
        'contactEmail',
        'adminpassword',
        'directory'
    );

    static public function getAuthorizedPsVersions()
    {
        // All 1.7 stable versions
        $psVersions = [
            "1.7.6.7",
            "1.7.6.6",
            "1.7.6.5",
            "1.7.6.4",
            "1.7.6.3",
            "1.7.6.2",
            "1.7.6.1",
            "1.7.6.0",
            "1.7.5.2",
            "1.7.5.1",
            "1.7.5.0",
            "1.7.4.4",
            "1.7.4.3",
            "1.7.4.2",
            "1.7.4.1",
            "1.7.4.0",
            "1.7.3.4",
            "1.7.3.3",
            "1.7.3.2",
            "1.7.3.1",
            "1.7.3.0",
            "1.7.2.5",
            "1.7.2.4",
            "1.7.2.3",
            "1.7.2.2",
            "1.7.2.1",
            "1.7.2.0",
            "1.7.1.2",
            "1.7.1.1",
            "1.7.1.0",
            "1.7.0.6",
            "1.7.0.5",
            "1.7.0.4",
            "1.7.0.3",
            "1.7.0.2",
            "1.7.0.1",
            "1.7.0.0"
        ];
        // Allow all 1.6.1 versions
        for ($i = 0; $i <= 24; $i++) {
            $psVersions[] = "1.6.1." . $i;
        }

        return $psVersions;
    }

    protected function configure()
    {
        $this
            ->setName('install:install')
            ->setDescription('install prestashop')
            ->addOption('psVersion', null, InputOption::VALUE_OPTIONAL, 'Prestashop version')
            ->addOption('domainName', null, InputOption::VALUE_OPTIONAL, 'domainName')
            ->addOption('dbname', null, InputOption::VALUE_OPTIONAL, 'dbname')
            ->addOption('dbuser', null, InputOption::VALUE_OPTIONAL, 'dbuser')
            ->addOption('dbpassword', null, InputOption::VALUE_OPTIONAL, 'dbpassword')
            ->addOption('contactEmail', null, InputOption::VALUE_OPTIONAL, 'contactEmail')
            ->addOption('adminpassword', null, InputOption::VALUE_OPTIONAL, 'Adminpassword')
            ->addOption('directory', null, InputOption::VALUE_OPTIONAL, 'Install directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        // We need the exec function at some point in this script.
        $isExecAllowed = (function_exists('exec') &&  !strpos(ini_get("disable_functions"), "exec"));

        //Get options values if defined
        foreach ($this->_options as $option) {
            ${$option} = $input->getOption($option);
        }
        // PS Version selection
        if (!$psVersion) {
            $psVersion = new ChoiceQuestion('Please select the version to install', InstallCommand::getAuthorizedPsVersions());
            $psVersion->setErrorMessage('Option %s is invalid');
            $installVersion = $helper->ask($input, $output, $psVersion);
        } else {
            $installVersion = $psVersion;
            // Assert that the value is accepted
            if (!in_array($installVersion, InstallCommand::getAuthorizedPsVersions())) {
                $output->writeln('<error>The given PS version is invalid</error>');
                return 1;
            }
        }
        $output->writeln('<info>PS version '.$installVersion.' will be installed</info>');

        try {
            $archiveFilePath = getcwd().DIRECTORY_SEPARATOR."prestashop_".$installVersion.".zip";
            
            // Second Step : Download prestashop archive
            $output->writeln("<info>Start downloading archive</info>");
            $this->downloadPSArchive($installVersion, $archiveFilePath);
            $output->writeln("<info>File downloaded</info>");

            if ($isExecAllowed) {
                if (!$directory) {
                    $directoryQuestion = new Question("Install in a subdirectory (default: current directory)", ".");
                    $directory = $helper->ask($input, $output, $directoryQuestion);
                }
            } else {
                $output->writeln("<info>Prestashop will be installed in directory 'prestashop' in current directory");
                $directory = ".";
            }

            // Third Step extract archive (Default directory is "prestashop")
            $output->writeln("<info>Unziping file</info>");
            // Extract the archive to the destination
            $this->extractPSArchive($archiveFilePath, $directory);
            // Remove the archive only on success at the moment
            unlink($archiveFilePath);
            $output->writeln("<info>File unziped</info>");
        } catch (Exception $e) {
            $output->writeln("<error>".$e->getMessage()."</error>");
            return 1;
        }

        if ($directory != ".") {
            exec("mv ".$directory."/prestashop/* ".$directory."/ && rm -rf ".$directory."/prestashop");
            $installPath = $directory;
        } else {
            $installPath = 'prestashop';
        }

        $cliFilepath = $installPath.DIRECTORY_SEPARATOR."install".DIRECTORY_SEPARATOR."index_cli.php";
        if (!file_exists($cliFilepath)) {
            $output->writeln("<error>An error occured during the installation, the cli file can't be found</error>");
        }
        // If we can't exec commands, then the user must run the cli script manually
        if (!$isExecAllowed) {
            $output->writeln("<error>Exec function is needed to install prestashop with CLI</error>");
            $output->writeln("<error>Please install it manually</error>");
            exit();
        }
        /**
         * Fourth Step : CLI install
         * @todo Factorize questions + deals with options params
         */
        $output->writeln("<info>Please give information for CLI install : </info>");

        if (!$domainName) {
            $domainNameQuestion = new Question("Domain name (default: ' ') ", " ");
            $domainName = $helper->ask($input, $output, $domainNameQuestion);
        }

        if (!$dbname) {
            $dbnameQuestion = new Question("Db name : (default: prestashop_console )", "prestashop_console");
            $dbname = $helper->ask($input, $output, $dbnameQuestion);
        }

        if (!$dbuser) {
            $dbuserQuestion = new Question("Db user : (default: root )", "root");
            $dbuser = $helper->ask($input, $output, $dbuserQuestion);
        }

        if (!$dbpassword) {
            $dbpasswordQuestion = new Question("Db passord : (default: root )", "root");
            $dbpassword = $helper->ask($input, $output, $dbpasswordQuestion);
        }

        if (!$contactEmail) {
            $contactEmailQuestion = new Question("Admin email : (default: test@example.com )", "test@example.com");
            $contactEmail = $helper->ask($input, $output, $contactEmailQuestion);
        }

        if (!$adminpassword) {
            $adminpassQuestion = new Question("Admin password : (default: test12345678 )", "test12345678");
            $adminpassword = $helper->ask($input, $output, $adminpassQuestion);
        }

        $command = "php ".$cliFilepath." --domain=$domainName  --db_name=$dbname --db_user=$dbuser --db_password=$dbpassword --email=$contactEmail --password=$adminpassword";
        $command .= " 2>&1 >> install.log";

        $output->writeln("<info>Starting CLI install</info>");
        system($command, $retval);
        // Check that the command succeed
        if (0 !== $retval) {
            $output->writeln('<error>Errors occurs during installation</error>');
            return 1;
        }
        $output->writeln('<info>Installation successfull');
    }

    /**
     * Download the archive for a given version to a given location.
     * 
     * @param string $psVersion : The version to download (Ex 1.6.1.0)
     * @param string $targetFilePath : The full path to the target file
     * 
     * @throws Exception
     */
    protected function downloadPSArchive($psVersion, $targetFilePath)
    {
        $source = sprintf(InstallCommand::DOWNLOAD_URL_PATTERN, $psVersion);
        // Download via CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        // Check for call error
        if (FALSE === $data) {
            throw new Exception("An error occured during the download of the archive : " . curl_error($ch));
        }
        if (empty($data)) {
            throw new Exception("The request to get the archive was a success but the result is empty. Check download URL " . $source);
        }
        curl_close($ch);
        // Put the result to the given destination file
        $file = fopen($targetFilePath, "w+");
        fputs($file, $data);
        fclose($file);
    }

    /**
     * Extract a given PrestaShopArchive to a given destination directory
     * 
     * @param string $psArchiveFilePath : The full path to the archive file
     * @param string $destinationDirectory : The full path to the directory
     * 
     * @throws Exception
     */
    protected function extractPSArchive($psArchiveFilePath, $destinationDirectory)
    {
        // Assert that the directory exists
        if (!is_dir($destinationDirectory)) {
            throw new Exception("The destination directory of the extract does not exist.");
        }

        $zip = new \ZipArchive();
        // Archive file can't be opened
        if (!$zip->open($psArchiveFilePath)) {
            throw new Exception("Unable to open the downloaded archive");
        }
        $res = $zip->extractTo($destinationDirectory);
        $zip->close();
        if (FALSE === $res) {
            throw new Exception("Unable to unzip downloaded archive");
        }
        // The new versions of prestashop have a subarchive in the actual archive that we must unzip too
        $subarchivePath = $destinationDirectory.DIRECTORY_SEPARATOR."prestashop.zip";
        if (file_exists($subarchivePath)) {
            $zip = new \ZipArchive();
            if (!$zip->open($subarchivePath)) {
                throw new Exception("Unable to open the downloaded sub-archive");
            }
            $res = $zip->extractTo($destinationDirectory.DIRECTORY_SEPARATOR."prestashop");
            $zip->close();
            if (FALSE === $res) {
                $output->writeln("<error>Unable to unzip downloaded archive</error>");
                return 1;
            }
            unlink($subarchivePath);
        }
    }
}
