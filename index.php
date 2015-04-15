<?php
/**
 * Subbly - The PHP e-commerce that gets back to basics.
 *
 * @package  Subbly
 * @author   Benjamin Guedj, Michael Lefebvre, Nicolas Brousse
 */
define('LARAVEL_START', microtime(true));

require_once __DIR__ . '/vendor/autoload.php';

Patchwork\Utf8\Bootup::initMbstring();
Illuminate\Support\ClassLoader::register();

$app = new \Subbly\Framework\Application();
$app->setRootDirectory(__DIR__);
$app->setConfigDirectory(__DIR__ . '/config/');
$app->start();
$app->run();
