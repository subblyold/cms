<?php
/**
 * Subbly - The PHP e-commerce that gets back to basics.
 *
 * @package  Subbly
 * @author   Benjamin Guedj, Michael Lefebvre, Sebastien Penet
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = new \Subbly\Application();
$app->run();
