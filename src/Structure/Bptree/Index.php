<?php

namespace Shaoxia\Structure\Bptree;

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

    /**
     * @var Data
     *
     * 索引指向的具体数据,在叶节点中该属性才有值
     */
    private $data;

    /**
     * @var int
     *
     * 索引指向的BTNode的ID
     */
    private $next;

    public function __construct($index = 0, $next = 0, Data $data = null)
    {
        $this->index = $index;
        $this->next = $next;
        $this->data = $data;
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
     * @return Data
     */
    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        return $this->data->setData($data);
    }
}