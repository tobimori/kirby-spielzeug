<?php

use Kirby\Cms\App as Kirby;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('tobimori/spielzeug', [
	'config' => [
		'blocks' => [
			'idKey' => 'blockId', // Key to look for in block content to set the block's id attribute

			/**
			 * Functions for getting "next block" and "previous block" types
			 * Data attributes can be used for style targeting, when specific blocks are next to each other
			 *
			 * Can be customized, if you e.g. have a toggle to show/hide the navigation,
			 * in which case the prev block for the first block would not be the navigation anymore
			 */
			'getNextBlock' => fn($block) => $block->next() ? $block->next()->type() : 'footer',
			'getPrevBlock' => fn($block) => $block->prev() ? $block->prev()->type() : 'navigation',
		]
	],
	'siteMethods' => require_once __DIR__ . '/config/siteMethods.php',
	'blockMethods' => require_once __DIR__ . '/config/blockMethods.php',
	'collectionMethods' => require_once __DIR__ . '/config/collectionMethods.php',
	'fieldMethods' => require_once __DIR__ . '/config/fieldMethods.php',
]);
