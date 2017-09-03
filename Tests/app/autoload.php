<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/** @var ClassLoader $loader */
$loader = require __DIR__.'/../../vendor/autoload.php';
include_once __DIR__."/AppKernel.php";
$loader->add('', __DIR__.'/../src');
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
