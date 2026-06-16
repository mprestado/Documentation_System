<?php
// pre-install-fix.php

// We'll patch LogManager.php after composer install
$logManagerPath = __DIR__ . '/vendor/laravel/framework/src/Illuminate/Log/LogManager.php';

echo "This script needs to be run AFTER 'composer install'\n";
echo "Run: composer install && php pre-install-fix.php\n";