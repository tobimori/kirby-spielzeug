<?php

use Kirby\Cms\App as Kirby;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('tobimori/spielzeug', autoloader(__DIR__)->toArray([
  'icons' => [],
  'siteMethods' => require_once __DIR__ . '/config/siteMethods.php',
  'blockMethods' => require_once __DIR__ . '/config/blockMethods.php',
  'collectionMethods' => require_once __DIR__ . '/config/collectionMethods.php',
]));
