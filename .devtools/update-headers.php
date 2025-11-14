#!/usr/bin/env php
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

$licenseFile = __DIR__ . '/licence.txt';
$projectRoot = dirname(__DIR__);

if (!file_exists($licenseFile)) {
    echo "Error: licence.txt not found at $licenseFile\n";
    exit(1);
}

// Read the new license block (already formatted with /** */)
$newLicenseBlock = file_get_contents($licenseFile);
$newLicenseBlock = trim($newLicenseBlock);

// Function to find PHP files
function findPhpFiles($dir) {
    $excludeDirs = ['vendor', 'phar', '.git', 'node_modules'];
    $phpFiles = [];
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $path = $file->getPathname();
            $excluded = false;
            foreach ($excludeDirs as $excludeDir) {
                if (strpos($path, DIRECTORY_SEPARATOR . $excludeDir . DIRECTORY_SEPARATOR) !== false) {
                    $excluded = true;
                    break;
                }
            }
            if (!$excluded) {
                $phpFiles[] = $path;
            }
        }
    }
    
    return $phpFiles;
}

// Function to replace license header
function updateLicenseHeader($filePath, $newLicenseBlock) {
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    // Check if file starts with shebang
    $hasShebang = strpos($content, '#!') === 0;
    $shebang = '';
    
    if ($hasShebang) {
        $lines = explode("\n", $content, 2);
        $shebang = $lines[0] . "\n";
        $content = isset($lines[1]) ? $lines[1] : '';
    }
    
    // Remove <?php tag temporarily
    $hasPhpTag = preg_match('/^<\?php\s*\n?/s', $content);
    if ($hasPhpTag) {
        $content = preg_replace('/^<\?php\s*\n?/s', '', $content);
    }
    
    // Remove all existing comment blocks at the start
    // This handles /** */ and /* */ style comments
    while (preg_match('/^\s*\/\*/', $content)) {
        $content = preg_replace('/^\s*\/\*.*?\*\/\s*/s', '', $content, 1);
    }
    
    // Remove leading whitespace
    $content = ltrim($content);
    
    // Build new content
    $newContent = $shebang;
    $newContent .= "<?php\n";
    $newContent .= $newLicenseBlock . "\n\n";
    $newContent .= $content;
    
    return $newContent;
}

// Main execution
echo "Starting license header update...\n\n";

$phpFiles = findPhpFiles($projectRoot);
echo "Found " . count($phpFiles) . " PHP files to process\n\n";

$updated = 0;
$errors = 0;

foreach ($phpFiles as $file) {
    try {
        $relativePath = str_replace($projectRoot . DIRECTORY_SEPARATOR, '', $file);
        echo "Processing: $relativePath ... ";
        
        $newContent = updateLicenseHeader($file, $newLicenseBlock);
        file_put_contents($file, $newContent);
        
        echo "✓\n";
        $updated++;
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n" . str_repeat('=', 60) . "\n";
echo "Update complete!\n";
echo "Files updated: $updated\n";
echo "Errors: $errors\n";
echo str_repeat('=', 60) . "\n";

exit($errors > 0 ? 1 : 0);
