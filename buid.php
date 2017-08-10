<?php
/**
 * Script to release a new version
 */
$binDir = dirname(__FILE__).'/bin/';
$versionFile = $binDir.'/phar/current.version';

shell_exec('php '.$binDir.'phar/box.phar build');
$shaFile = sha1_file($binDir.'/prestashopConsole.phar');
unlink($versionFile);
file_put_contents($versionFile, $shaFile);
