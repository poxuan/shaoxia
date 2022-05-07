<?php

namespace Module\Structure\Bptree;

/**
 * Class Index
 *
 * 索引对象
 */
class Index
{
    /**
     * @var int
     *
     * 索引值
     */
    private $index;

    private $data;

    /**
     * @var int
     *
     * 索引指向的BTNode的ID
     */
    private $next;

    public function __construct($index = 0, $next = 0, $data = null)
    {
        $this->index = $index;
        $this->next = $next;
        $this->data = $data instanceof Data ? $data->toArray() : $data;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getNext()
    {
        return $this->next;
    }

    /**
     * @return Array
     */
    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data =  array_merge($this->data, $data);
    }
}