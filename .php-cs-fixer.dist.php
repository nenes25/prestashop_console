<?php

$config = new PrestaShop\CodingStandards\CsFixer\Config();

$config
    ->setUsingCache(true)
    ->getFinder()
    ->in(__DIR__.'/src/PrestashopConsole/')
    ->exclude('./src/vendor');

return $config;