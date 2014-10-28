<?php

$testEnvironment = 'testing';

$config = require("app/config/{$testEnvironment}/database.php");

extract($config['connections'][$config['default']]);

$connection = new \PDO("{$driver}:host={$host}", $username, $password);
$connection->query("DROP DATABASE IF EXISTS ".$database);
$connection->query("CREATE DATABASE ".$database);

// run migrations for packages
print('Running migrations'."\n");
foreach(glob('vendor/*/*', GLOB_ONLYDIR) as $package)
{
    $packageName = substr($package, 7); // drop "vendor" prefix
    passthru("./artisan migrate --package={$packageName} --env={$testEnvironment} > /dev/null");
    print('.');
}
print("\n");

require('autoload.php'); // run laravel's original bootstap file
