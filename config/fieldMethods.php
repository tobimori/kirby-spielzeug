<?php

use Kirby\Content\Field;
use Kirby\Http\Url;
use Kirby\Toolkit\Str;

return [
  // based on:
  // https://github.com/getkirby/kirby/blob/v4/develop/panel/src/components/Forms/Field/LinkField.vue#L127
  'linkType' => function (Field $field) {
    $val = $field->value();
    if (empty($val)) return 'custom';

    if (Str::match($val, '/^(http|https):\/\//')) {
      return 'url';
    }

    if (Str::startsWith($val, 'page://') || Str::startsWith($val, '/@/page/')) {
      return 'page';
    }

    if (Str::startsWith($val, 'file://') || Str::startsWith($val, '/@/file/')) {
      return 'file';
    }

    if (Str::startsWith($val, 'tel:')) {
      return 'tel';
    }

    if (Str::startsWith($val, 'mailto:')) {
      return 'email';
    }

    if (Str::startsWith($val, '#')) {
      return 'anchor';
    }

    return 'custom';
  },
  'urlType' => function (Field $field) {
    return $field->linkType();
  },
  'linkTitle' => function (Field $field) {
    $val = $field->value();
    $type = $field->linkType();

    switch ($type) {
      case 'url':
        return Url::short($val);
      case 'page':
        $page = $field->toPage();
        if ($page) return $page->title();
      case 'file':
        $file = $field->toFile();
        if ($file) return $file->filename();
      case 'email':
        return Str::replace($val, 'mailto:', '');
      case 'tel':
        return Str::replace($val, 'tel:', '');
      default:
        return $val;
    };
  }
];
