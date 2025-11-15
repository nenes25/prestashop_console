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
 * https://github.com/nenes25/prestashop_console
 * https://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole\Command\Console;

use Exception;
use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Self-update command using GitHub Releases
 */
class SelfUpdateCommand extends Command
{
    const GITHUB_API_URL = 'https://api.github.com/repos/nenes25/prestashop_console/releases/latest';

    protected function configure(): void
    {
        $this
            ->setName('console:self-upgrade')
            ->setDescription('Upgrade console to latest version (phar only)');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        // Check if running as phar
        if (!$this->isPhar()) {
            $output->writeln('<error>This command can only be run in phar mode</error>');

            return self::RESPONSE_ERROR;
        }

        try {
            // 1. Get latest release info from GitHub
            $output->writeln('<info>Checking for updates...</info>');
            $releaseInfo = $this->getLatestRelease();

            // 2. Compare versions
            $currentVersion = $this->getApplication()->getVersion();
            $latestVersion = ltrim($releaseInfo['tag_name'], 'v');

            if (version_compare($latestVersion, $currentVersion, '<=')) {
                $output->writeln('<info>Already up to date (' . $currentVersion . ')</info>');

                return self::RESPONSE_SUCCESS;
            }

            // 3. Download new phar
            $output->writeln('<info>Downloading version ' . $releaseInfo['tag_name'] . '...</info>');
            $pharUrl = $this->getPharDownloadUrl($releaseInfo);
            $newPharContent = file_get_contents($pharUrl);

            if ($newPharContent === false) {
                throw new Exception('Failed to download phar file');
            }

            // 4. Verify checksum if available
            $this->verifyChecksum($releaseInfo, $newPharContent, $output);

            // 5. Backup current phar
            $pharPath = \Phar::running(false);
            $backupPath = $pharPath . '.backup';
            if (!copy($pharPath, $backupPath)) {
                throw new Exception('Failed to create backup');
            }

            // 6. Replace phar
            if (!file_put_contents($pharPath, $newPharContent)) {
                throw new Exception('Failed to write new phar file');
            }
            chmod($pharPath, 0755);

            $output->writeln('<info>✓ Updated successfully to ' . $releaseInfo['tag_name'] . '</info>');
            $output->writeln('<comment>Backup saved to: ' . basename($backupPath) . '</comment>');
        } catch (Exception $e) {
            $output->writeln('<error>Update failed: ' . $e->getMessage() . '</error>');

            return self::RESPONSE_ERROR;
        }

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Check if running as phar
     *
     * @return bool
     */
    private function isPhar(): bool
    {
        return strlen(\Phar::running(false)) > 0;
    }

    /**
     * Get latest release info from GitHub
     *
     * @return array
     *
     * @throws Exception
     */
    private function getLatestRelease(): array
    {
        $context = stream_context_create([
            'http' => [
                'user_agent' => 'PrestashopConsole',
                'timeout' => 10,
            ],
        ]);

        $response = @file_get_contents(self::GITHUB_API_URL, false, $context);

        if ($response === false) {
            throw new Exception('Failed to fetch release information from GitHub');
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Failed to parse GitHub API response');
        }

        return $data;
    }

    /**
     * Get phar download URL from release assets
     *
     * @param array $releaseInfo
     *
     * @return string
     *
     * @throws Exception
     */
    private function getPharDownloadUrl(array $releaseInfo): string
    {
        if (!isset($releaseInfo['assets']) || !is_array($releaseInfo['assets'])) {
            throw new Exception('No assets found in release');
        }

        foreach ($releaseInfo['assets'] as $asset) {
            if ($asset['name'] === 'prestashopConsole.phar') {
                return $asset['browser_download_url'];
            }
        }

        throw new Exception('PHAR file not found in release assets');
    }

    /**
     * Verify checksum if available
     *
     * @param array $releaseInfo
     * @param string $content
     * @param OutputInterface $output
     *
     * @return void
     *
     * @throws Exception
     */
    private function verifyChecksum(array $releaseInfo, string $content, OutputInterface $output): void
    {
        foreach ($releaseInfo['assets'] as $asset) {
            if ($asset['name'] === 'prestashopConsole.phar.sha256') {
                $checksumUrl = $asset['browser_download_url'];
                $checksumContent = @file_get_contents($checksumUrl);

                if ($checksumContent === false) {
                    $output->writeln('<comment>Warning: Could not download checksum file</comment>');

                    return;
                }

                $expectedChecksum = trim(explode(' ', $checksumContent)[0]);
                $actualChecksum = hash('sha256', $content);

                if ($expectedChecksum !== $actualChecksum) {
                    throw new Exception('Checksum verification failed');
                }

                $output->writeln('<info>✓ Checksum verified</info>');

                return;
            }
        }

        $output->writeln('<comment>Warning: No checksum available</comment>');
    }
}
