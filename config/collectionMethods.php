<?php

return [
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
