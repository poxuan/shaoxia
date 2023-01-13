<?php

namespace Shaoxia\Support;

use ArrayAccess;
use Countable;
use JsonSerializable;
use Shaoxia\Contracts\Arrayable;

/**
 * Class File
 * @package App\Exceptions
 */
class Collection implements ArrayAccess,Countable,Arrayable
{
    protected $items = [];

    public function __construct($items = [])
    {
        $this->items = $this->getArrayableItems($items);
    }

    /**
     * Results array of items from Collection or Arrayable.
     *
     * @param  mixed  $items
     * @return array
     */
    protected function getArrayableItems($items)
    {
        if (is_array($items)) {
            return $items;
        } elseif ($items instanceof self) {
            return $items->all();
        } elseif ($items instanceof Arrayable) {
            return $items->toArray();
        }

        return (array) $items;
    }

    public function all() {
        return $this->items;
    }

    public function pull($item) {
        $this->items[] = $item;
    }

    public function pop() {
        return array_pop($this->items);
    }
    

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset,  $value)
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function toArray() {
        return array_map(function($item) {
            return $item instanceof Arrayable ? $item->toArray(): $item;
        }, $this->items);
    }
}