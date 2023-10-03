<?php

namespace tobimori\Spielzeug;

use Closure;
use Kirby\Cms\App;
use Kirby\Cms\Page;

/**
 * Helper class for customizing the panel menu
 *
 * Based on original work by Lukas Kleinschmidt
 * https://gist.github.com/lukaskleinschmidt/247a957ebcde66899757a16fead9a039
 */
class Menu
{
	public static array $pages = [];

	public static string $path;

	public static function path(): string
	{
		return static::$path ??= App::instance()->request()->path()->toString();
	}

	public static function page(string $label, string $icon = null, string|Page $link = null, Closure|bool $current = null): array
	{
		if ($link instanceof Page) {
			$link = $link->panel()->url();
		}

		if (is_null($link)) {
			return [];
		}

		return static::$pages[] = [
			'label' => t($label, $label),
			'link' => $link,
			$icon && 'icon' => $icon,
			'current' => $current ?? fn () =>
			str_contains(static::path(), $link)
		];
	}

	public static function site(string $label, string $icon = 'home'): array
	{
		return [
			'label' => t($label, $label),
			'current' => function (string $id = null) {
				if ($id !== 'site') {
					return false;
				}

				foreach (static::$pages as &$page) {
					if (str_contains(static::path(), $page['link'])) {
						return false;
					}
				}

				return true;
			},
		];
	}
}
