<?php

return [
  'attr' => function ($attr) {
    $attr = array_merge_recursive($attr, [
      'class' => [option('tobimori.spielzeug.blocks.defaultClass')],
      'data-block-id' => $this->id()
    ]);

    if (($blockId = $this->content()->get(option('tobimori.spielzeug.blocks.idKey', 'blockId')))->isNotEmpty()) {
      $attr['id'] = $blockId->value();
    }

    $attr['data-prev-block'] = option('tobimori.spielzeug.blocks.getPrevBlock', fn ($block) => $block->prev() ? $block->prev()->type() : 'navigation')($this);
    $attr['data-next-block'] = option('tobimori.spielzeug.blocks.getNextBlock', fn ($block) => $block->next() ? $block->next()->type() : 'footer')($this);

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
