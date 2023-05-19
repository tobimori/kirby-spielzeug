<?php

use Kirby\Cms\Collection;

return [
  /** 
   * Repeats items in a collection `$times` times 
   */
  'repeat' => function (int $times) {
    $collection = [];

    if ($times <= 0) { // 0 or negative, return empty collection
      return new Collection();
    }

    if ($times <= 1) { // 1, return the same collection
      return $this;
    }

    foreach (range(1, $times) as $_) { // loop through all items, create a new collection
      foreach ($this as $item) {
        $collection[] = $item;
      }
    }

    // convert the array of chunks to a collection
    $result = clone $this;
    $result->data = $collection;

    return $result;
  },
  /** 
   * Fills a collection with the same items until target size is reached 
   */
  'fill' => function (int $target) {
    $count = $this->count();

    if ($target <= $count) {
      return $this->limit($target);
    }

    $parts = ceil($target / $count);
    return $this->repeat($parts)->limit($target);
  },
  /**
   * Splits a Kirby collection into a given number of parts 
   */
  'partition' => function (int $parts) {
    $count = $this->count();
    $partSize = floor($count / $parts);
    $partRem = $count % $parts;
    $chunks = [];
    $mark = 0;

    if ($count < $parts) {
      return $this->chunk(1);
    }

    foreach (range(1, $parts) as $px) {
      // equally distribute rest to first parts
      $incr = ($px <= $partRem) ? $partSize + 1 : $partSize;
      $chunks[$px] = array_slice($this->data, $mark, $incr);
      $mark += $incr;
    }

    // convert each chunk to a sub collection
    $collection = [];

    foreach ($chunks as $items) {
      // we clone $this instead of creating a new object because
      // different objects may have different constructors
      $clone = clone $this;
      $clone->data = $items;

      $collection[] = $clone;
    }

    // convert the array of chunks to a collection
    $result = clone $this;
    $result->data = $collection;

    return $result;
  }
];
