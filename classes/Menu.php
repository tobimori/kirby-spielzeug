<?php

namespace tobimori\Spielzeug;

use Closure;
use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Pages;

/**
 * Helper class for customizing the panel menu
 *
 * Based on original work by Lukas Kleinschmidt
 * https://gist.github.com/lukaskleinschmidt/247a957ebcde66899757a16fead9a039
 */
class Menu
{
	protected static array $favorites = [];
	protected static array $pages = [];

	/**
	 * Returns the current panel request path
	 */
	public static function path(): string
	{
		return App::instance()->request()->path()->toString();
	}

	/**
	 * Internal method to determine current state
	 */
	protected static function isCurrent(?string $link, array ...$ignore): bool
	{
		if ($link && !str_contains(static::path(), $link)) {
			return false;
		}

		foreach (array_merge(...$ignore) as $page) {
			if (str_contains(static::path(), $page)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Check if current path matches this specific page
	 * Uses longest prefix matching to handle infinite nesting
	 */
	protected static function isCurrentPage(?string $link, array ...$ignore): bool
	{
		$currentPath = static::path();
		
		$panelSlug = App::instance()->option('panel.slug', 'panel');
		if (str_starts_with($currentPath, $panelSlug . '/')) {
			$currentPath = substr($currentPath, strlen($panelSlug) + 1);
		}
		
		foreach (array_merge(...$ignore) as $page) {
			if (str_contains($currentPath, $page)) {
				return false;
			}
		}
		
		if (!$link) {
			return false;
		}
		
		$isPrefix = false;
		
		if ($currentPath === $link) {
			$isPrefix = true;
		} elseif (str_starts_with($currentPath, $link . '+')) {
			$isPrefix = true;
		} elseif (str_starts_with($currentPath, $link . '/')) {
			$isPrefix = true;
		}
		
		if (!$isPrefix) {
			return false;
		}
		
		foreach (static::$pages as $menuPage) {
			if ($menuPage === $link) {
				continue;
			}
			
			if ($currentPath === $menuPage || 
				str_starts_with($currentPath, $menuPage . '+') || 
				str_starts_with($currentPath, $menuPage . '/')) {
				if (strlen($menuPage) > strlen($link)) {
					return false;
				}
			}
		}
		
		return true;
	}

	/**
	 * Returns the panel.menu option for a specific link or page, ignores all favorites
	 */
	public static function page(?string $label = null, ?string $icon = null, string|Page|null $link = null, Closure|bool|null $current = null): array
	{
		if ($link instanceof Page) {
			$page = $link;
			$link = $link->panel()->path();
		}

		if (is_null($link)) {
			return [];
		}

		$data = [
			'label' => $label ?? ($page->title()->value() ?? ''),
			'link' => static::$pages[] = $link,
			'current' => $current ?? fn() => static::isCurrentPage($link, static::$favorites),
		];

		if ($icon) {
			$data['icon'] = $icon;
		}

		return $data;
	}

	/**
	 * Returns the site panel.menu option, ignores all custom pages
	 */
	public static function site(?string $label = null, ?string $icon = null): array
	{
		$data = [
			'current' => fn(?string $id = null) => $id === 'site' && static::isCurrent(null, static::$favorites, static::$pages),
		];

		if ($label) {
			$data['label'] = t($label, $label);
		}

		if ($icon) {
			$data['icon'] = $icon;
		}

		return $data;
	}

	/**
	 * Registers a Pages collection as users favorites,
	 * and returns an array to be used inside the panel.menu option
	 */
	public static function favorites(Pages $favorites): array
	{
		$data = [];

		foreach ($favorites as $fav) {
			$data["favorites/{$fav->uuid()->id()}"] = [
				'label' => $fav->title(),
				'icon' => 'star',
				'link' => static::$favorites[] = $link = $fav->panel()->path(),
				'current' => fn() => static::isCurrent($link),
			];
		}

		if (!empty($data)) {
			$data[] = '-';
		}

		return $data;
	}
}
