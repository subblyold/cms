<?php

use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Resources')
    ->exclude('Tests')
    ->in($dir = __DIR__ . '/../../')
;

// $versions = GitVersionCollection::create($dir)
// //     ->addFromTags('v2.0.*')
// //     ->add('2.0', '2.0 branch')
//     ->add('master', 'master branch')
// ;

return new Sami($iterator, array(
    'theme'                => 'default',
    // 'versions'             => $versions,
    'title'                => 'Subbly API',
    'build_dir'            => __DIR__.'/../../../build/%version%',
    'cache_dir'            => __DIR__.'/../../../cache/%version%',
    'default_opened_level' => 3,
));
