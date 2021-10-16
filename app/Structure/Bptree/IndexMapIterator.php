<?php

namespace App\Structure\Bptree;



/**
 * Class IndexMapIterator
 *
 * BTNode的indexMap的迭代器
 */
class IndexMapIterator implements \Iterator
{
    private $indexMap = [];
    private $position = 0;

    public function __construct($indexMap = [])
    {
        $this->indexMap = $indexMap;
        $this->position = 0;
    }

    /**
     * @return Index
     */
    public function current()
    {
        return $this->indexMap[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    public function prev()
    {
        $this->position--;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->indexMap[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

}
