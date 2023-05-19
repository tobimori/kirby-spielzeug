<?php

return [
  'attr' => function ($attr) {
    $attr = array_merge_recursive($attr, ['class' => ['o-block'], 'data-block-id' => $this->id()]);

    if ($this->blockId()->isNotEmpty()) {
      $attr['id'] = $this->blockId();
    }

    $attr['data-prev-block'] = $this->prev() ? $this->prev()->type() : 'navigation';
    $attr['data-next-block'] = $this->next() ? $this->next()->type() : 'footer';

    $s = "";
    foreach ($attr as $key => $value) {
      if (is_array($value)) {
        $value = implode(' ', $value);
      }

      $s .= $key . '="' . $value . '" ';
    }

    return $s;
  }
];
