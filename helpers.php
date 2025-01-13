<?php

use Kirby\Cms\App;
use Kirby\Filesystem\Asset;
use Kirby\Filesystem\F;
use Kirby\Toolkit\Str;

/**
 * TODO: Refactor Localized File Helpers to class
 */

/**
 * Returns the first existing files' contents 
 * by either its relative or absolute path.
 */
function firstExistingFile(array $files): string|false
{
  $root = App::instance()->root();

  foreach ($files as $file) {
    if (F::exists($file))
      return $file;

    if (F::exists($absFile = $root . '/' . $file))
      return $absFile;
  }

  return false;
}

/**
 * Returns a file path by absolute or relative path with using localized file options
 */
function localizedFile(string $file, string $type, ?string $locale = null): string|false
{

  if (!is_string($file)) {
    return false;
  }

  $fallback = kirby()->languages()->default()->code() ?? 'de';

  if (!is_string($locale)) {
    $locale = kirby()->language()->code();
  }

  return firstExistingFile([$file . '.' . $locale . '.' . $type, $file . '.' . $fallback . '.' . $type, $file . '.' . $type]);
}

/**
 * Returns Asset object by absolute or relative path with using localized file options
 */
function localizedAsset(string $file, string $type, ?string $locale = null): Asset|false
{
  if (!is_string($file)) {
    return false;
  }

  $fallback = kirby()->languages()->default()->code() ?? 'de';

  if (!is_string($locale)) {
    $locale = kirby()->language()->code();
  }

  foreach ([$file . '.' . $locale . '.' . $type, $file . '.' . $fallback . '.' . $type, $file . '.' . $type] as $file) {
    if (F::exists($file)) return asset($file);
    if (F::exists(App::instance()->root() . '/' . $file)) return asset($file);
  }

  return false;
}

/**
 * Returns the SVG files' path to be used with an img Tag 
 */
function localizedSvgUrl(string $file, ?string $locale = null): string|false
{
  return localizedFile($file, 'svg', $locale);
}

/**
 * Includes an SVG file by absolute or relative path with using localized file options
 */
function localizedSvg(string $file, ?string $locale = null): string|false
{
  return ($filePath = localizedSvgUrl($file, $locale)) ? F::read($filePath) : false;
}

if (!function_exists('tstrip')) {
  /**
   * Returns the stripped version (without HTML tags) of a translated string
   */
  function tstrip(string $key, ?string $fallback, ?string $locale = null): string
  {
    return Str::unhtml(t($key, $fallback, $locale));
  }
}

if (!function_exists('median')) {
  /**
   * Returns the median of an array
   * */
  function median(array $array): mixed
  {
    $count = count($array);
    if ($count == 0) {
      return 0;
    }
    $middle = floor(($count - 1) / 2);
    sort($array, SORT_NUMERIC);
    if ($count % 2) {
      return $array[$middle];
    } else {
      return ($array[$middle] + $array[$middle + 1]) / 2;
    }
  }
}
