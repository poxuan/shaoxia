<?php

namespace Module\Structure\Bptree;
/**
 * Class BTNode
 *
 * B+树节点
 */
class BTNode
{
    /**
     * @var int
     *
     * 标识节点对象的唯一值
     */
    private $id = 0;

    /**
     * @var int
     *
     * 父节点的ID值
     */
    public $parent = 0;

    /**
     * @var bool
     *
     * 是否是叶节点
     */
    public $isLeaf = false;

    /**
     * @var int
     *
     * 当前索引的数量,一旦该值超过树的阶,该节点就需要分裂
     */
    public $indexNum = 0;

    /**
     * @var array
     *
     * 索引对象列表
     */
    private $indexMap = [];

    /**
     * @var int
     *
     * 下一个兄弟节点的ID值(该属性仅针对叶子节点)
     */
    public $next = 0;

    public function __construct($isLeaf = false, $parent = 0)
    {
        $this->init($isLeaf, $parent);
    }

    protected function init($isLeaf, $parent)
    {
        $this->id = uniqid();
        $this->isLeaf = $isLeaf;
        $this->parent = $parent;
        $this->indexMap = [];
    }

    public function getID()
    {
        return $this->id;
    }

    /**
     * @param Index $index
     *
     * 向树节点中添加新的索引对象,添加完成后需要按索引值升序排序
     */
    public function addIndex(Index $index)
    {
        array_push($this->indexMap, $index);
        usort($this->indexMap, function (Index $a, Index $b) {
            if ($a->getIndex() == $b->getIndex()) {
                return 0;
            }
           return $a->getIndex() > $b->getIndex() ? 1 : -1;
        });
        $this->indexNum++;
    }

    /**
     * @return IndexMapIterator
     *
     * 生成indexMap的迭代器
     */
    public function generateIndexMapIterator()
    {
        return new IndexMapIterator($this->indexMap);
    }

    /**
     * @param $order
     * @return bool
     *
     * 判断该节点是否已满,当前的索引对象树超过树的阶即为满.
     */
    public function isFull($order)
    {
        return $this->indexNum > $order;
    }

    public function deleteMap($start)
    {
        $count = 0;
        for ($i = $start; $i < $this->indexNum; $i++) {
            $count++;
            unset($this->indexMap[$i]);
        }

        $this->indexNum = $this->indexNum - $count;
    }

    public function updateParent($id)
    {
        $this->parent = $id;
    }

    public function setNext($id)
    {
        $this->next = $id;
    }
}