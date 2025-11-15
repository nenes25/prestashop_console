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

echo "Building PrestashopConsole PHAR...\n";

$pharDir = __DIR__ . '/phar/';
$pharFile = $pharDir . 'prestashopConsole.phar';
$checksumFile = $pharDir . 'prestashopConsole.phar.sha256';

// 1. Build PHAR with Box
echo "→ Compiling PHAR with Box...\n";
$output = shell_exec('php ' . $pharDir . 'box.phar compile 2>&1');
echo $output;

// 2. Check if PHAR was created
if (!file_exists($pharFile)) {
    echo "✗ Error: PHAR file not created\n";
    exit(1);
}

// 3. Generate SHA256 checksum
echo "→ Generating SHA256 checksum...\n";
$sha256 = hash_file('sha256', $pharFile);
file_put_contents($checksumFile, $sha256 . "  prestashopConsole.phar\n");

// 4. Display info
$pharSize = round(filesize($pharFile) / 1024 / 1024, 2);
echo "\n✓ Build successful!\n";
echo "  File: " . $pharFile . "\n";
echo "  Size: " . $pharSize . " MB\n";
echo "  SHA256: " . $sha256 . "\n";
echo "\nTo test locally:\n";
echo "  php phar/prestashopConsole.phar --version\n";
echo "  php phar/prestashopConsole.phar list\n";
