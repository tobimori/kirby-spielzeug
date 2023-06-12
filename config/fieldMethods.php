<?php

use Kirby\Cms\Field;
use Kirby\Toolkit\Str;

return [
  // based on:
  // https://github.com/getkirby/kirby/blob/v4/develop/panel/src/components/Forms/Field/LinkField.vue#L185
  'urlType' => function (Field $field) {
    $val = $field->value();

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

    return 'url';
  }
];
