<?php

namespace Module\Structure\Bptree;

use Shaoxia\Exceptions\CustomException;

/**
 * Class BPlusTree
 *
 * B+树
 */
class BPlusTree
{
    /**
     * @var int
     *
     * 根节点ID
     */
    public $root = 0;

    /**
     * @var array
     * 节点池: 节点的id为key, 节点对象为value
     */
    private $nodeMap = [];

    /**
     * @var int
     *
     * B+树的阶
     */
    private $order;

    public function __construct($order = 3)
    {
        $this->order = $order;
    }

    /**
     * @var array
     * 筛选节点ID组
     */
    private $minNodeID = null;
    private $maxNodeID = null;

    /**
     * @param Data $record
     *
     * 写入数据
     */
    public function insert(Data $record)
    {
        $index = $record->getIndex();
        if (empty($index)) { // 自增操作
            $index = $this->maxNodeID + 1;
            $record->setIndex($index);
        }
        if ($this->isEmpty()) {
            //树为空,直接创建一个根节点,此节点是叶节点.
            $node = new BTNode( true,0);
            $node->addIndex(new Index($index, 0, $record));
            $this->storeNode($node);
            $this->root = $node->getID();
            $this->minNodeID = $index;
            $this->maxNodeID = $index;
        } else {
            $tmpNode = $this->getNodeByID($this->root);
            $prevNode = $tmpNode;

            //定位需要插入的叶节点
            while ($tmpNode != null) {
                $prevNode = $tmpNode;
                $indexMapIterator = $tmpNode->generateIndexMapIterator();
                //需要处理的下一个树节点是该节点中第一个大于要写入索引值索引对象的上一个兄弟索引对象指向的树节点
                while ($indexMapIterator->valid()) {
                    $indexObj = $indexMapIterator->current();

                    if ($index > $indexObj->getIndex()) {
                        $indexMapIterator->next();
                    } else if ($index == $indexObj->getIndex()) {
                        //树中已经存在相同的索引,不做处理.
                        return false;
                    } else {
                        break;
                    }
                }
                $indexMapIterator->prev();
                $currentIndex = $indexMapIterator->current();
                $tmpNode = $this->getNodeByID($currentIndex->getNext());
            }
            //叶子节点中保存具体的值.
            $prevNode->addIndex(new Index($index, 0, $record));
            //树节点需要分裂
            if ($prevNode->isFull($this->order)) {
                $this->split($prevNode);
            }
            if  ($index < $this->minNodeID) {
                $this->minNodeID = $index;
            } elseif ($index > $this->maxNodeID) {
                $this->maxNodeID = $index;
            }
        } 
    }

    public function update($index, $data)
    {
        $tmpNode = $this->getNodeByID($this->root);
        while ($tmpNode != null) {
            $indexMapIterator = $tmpNode->generateIndexMapIterator();
            while ($indexMapIterator->valid()) {
                $indexObj = $indexMapIterator->current();
                if ($index > $indexObj->getIndex()) {
                    $indexMapIterator->next();
                } else if ($index == $indexObj->getIndex()) {
                    //只有叶节点中索引值相同的索引对象才持有具体数据.
                    if ($tmpNode->isLeaf) {
                        $indexObj->setData($data);
                        return 1;
                    } else {
                        $indexMapIterator->next();
                    }
                } else {
                    break;
                }
            }
            $indexMapIterator->prev();
            $tmpNode = $this->getNodeByID($indexMapIterator->current()->getNext());
        }
        return 0;
    }

    /**
     * @param BTNode $node
     *
     * 分裂节点
     *
     */
    protected function split(BTNode $node)
    {
        //获取中间索引,创建新的索引
        $middle = intval(ceil($node->indexNum/2));
        $middleIndexValue = 0;

        $pid = $node->parent;

        //分裂节点为根节点时,树高度+1,创建新节点作为根节点.
        if ($pid == 0) {
            $parent = new BTNode(false, 0);
            $this->storeNode($parent);
            $parent->addIndex(new Index(0, $node->getID()));
            $pid = $parent->getID();

            //新节点作为根节点
            $this->root = $pid;
        }
        $parent = $this->getNodeByID($pid);

        //新树节点的父节点是原节点的父节点,若原节点是根节点,则新树节点的父节点是新的根节点.
        $newNode = new BTNode($node->isLeaf, $pid);
        $this->storeNode($newNode);

        $indexMapIterator = $node->generateIndexMapIterator();
        while ($indexMapIterator->valid()) {

            //将中间索引及之后的索引,移动到新节点
            if ($indexMapIterator->key() >= $middle) {
                $indexObj = $indexMapIterator->current();
                $newNode->addIndex(new Index($indexObj->getIndex(), $indexObj->getNext(), $indexObj->getData()));
                $tmp = $this->getNodeByID($indexObj->getNext());
                //索引指向子节点的原始父节点是分裂之前的节点,现在更改为它们移动到的新节点
                if ($tmp != null) {
                    $tmp->updateParent($newNode->getID());
                }
                if ($indexMapIterator->key() == $middle) {
                    $middleIndexValue = $indexObj->getIndex();
                }
            }
            $indexMapIterator->next();
        }

        //原节点的父节点更新为新的父节点(原节点为根节点时,会重新创建根节点,此时原节点的父节点是这个新的根节点)
        $node->updateParent($pid);

        //原节点分裂后,中间索引及之后的索引都被移动到了新节点,所以把移动的索引在原节点中删除
        $node->deleteMap($middle);

        //B+树的叶子节点之间形成一个链表,在原节点分裂后,原节点的next指向新节点,新节点的next指向原节点的next
        if ($node->isLeaf) {
            $newNode->setNext($node->next);
            $node->setNext($newNode->getID());
        }

        //向分裂节点的父节点添加索引对象,该索引对象的索引值是分裂节点的中间索引值,指向的是新创建的树节点
        $parent->addIndex(new Index($middleIndexValue, $newNode->getID()));

        //若分裂节点的父节点索引达到上限,需要分裂父节点
        if ($parent->isFull($this->order)) {
            $this->split($parent);
        }
    }

    /**
     * @param $index
     * @return Data|string
     *
     * 索引单条查询
     */
    public function find($index)
    {
        $tmpNode = $this->getNodeByID($this->root);
        while ($tmpNode != null) {
            $indexMapIterator = $tmpNode->generateIndexMapIterator();
            while ($indexMapIterator->valid()) {
                $indexObj = $indexMapIterator->current();
                if ($index > $indexObj->getIndex()) {
                    $indexMapIterator->next();
                } else if ($index == $indexObj->getIndex()) {
                    //只有叶节点中索引值相同的索引对象才持有具体数据.
                    if ($tmpNode->isLeaf) {
                        return $indexObj->getData();
                    } else {
                        $indexMapIterator->next();
                    }
                } else {
                    break;
                }
            }
            $indexMapIterator->prev();
            $tmpNode = $this->getNodeByID($indexMapIterator->current()->getNext());
        }
        throw new CustomException('record ['.$index. '] is not exists!');
    }

    public function delete()
    {
        //TODO
    }

    /**
     * @param $start
     * @param $end
     * @return array
     *
     * 范围查询
     */
    public function rangeFind($start, $end)
    {
        $index = $start;

        $tmpNode = $this->getNodeByID($this->root);
        $prevNode = $tmpNode;

        //根据start索引,定位到叶节点链表开始的节点.
        while ($tmpNode != null) {
            $prevNode = $tmpNode;
            $indexMapIterator = $tmpNode->generateIndexMapIterator();
            while ($indexMapIterator->valid()) {
                $indexObj = $indexMapIterator->current();
                if ($index >= $indexObj->getIndex()) {
                    $indexMapIterator->next();
                } else {
                    break;
                }
            }
            $indexMapIterator->prev();
            $tmpNode = $this->getNodeByID($indexMapIterator->current()->getNext());
        }

        $tNode = $prevNode;
        $resultData  = [];

        //从定位到的节点,遍历叶节点链表,查询出范围内的记录
        while ($tNode != null) {
            $indexMapIterator = $tNode->generateIndexMapIterator();
            while ($indexMapIterator->valid()) {
                $indexObj = $indexMapIterator->current();
                if ($indexObj->getIndex() > $end) {
                    break 2;
                }
                if ($indexObj->getIndex() >= $start) {
                    array_push($resultData, $indexObj->getData());
                }
                $indexMapIterator->next();
            }
            $tNode = $this->getNodeByID($tNode->next);
        }

        return $resultData;
    }

    public function filterFind(DataFilter $filter, $columns)
    {
        $index = $this->minNodeID;

        $tmpNode = $this->getNodeByID($this->root);
        $prevNode = $tmpNode;

        //根据start索引,定位到叶节点链表开始的节点.
        while ($tmpNode != null) {
            $prevNode = $tmpNode;
            $indexMapIterator = $tmpNode->generateIndexMapIterator();
            while ($indexMapIterator->valid()) {
                $indexObj = $indexMapIterator->current();
                if ($index >= $indexObj->getIndex()) {
                    $indexMapIterator->next();
                } else {
                    break;
                }
            }
            $indexMapIterator->prev();
            $tmpNode = $this->getNodeByID($indexMapIterator->current()->getNext());
        }

        $tNode = $prevNode;
        $resultData  = [];

        //从定位到的节点,遍历叶节点链表,查询出范围内的记录
        while ($tNode != null) {
            $indexMapIterator = $tNode->generateIndexMapIterator();
            while ($indexMapIterator->valid()) {
                $indexObj = $indexMapIterator->current();
                $data = $indexObj->getData();
                if ($filter->check($data)) {
                    $data = $filter->columns($data, $columns);
                    array_push($resultData, $data);
                }
                $indexMapIterator->next();
            }
            $tNode = $this->getNodeByID($tNode->next);
        }

        return $resultData;
    }

    public function isEmpty()
    {
        return $this->root === 0;
    }

    /**
     * @param BTNode $node
     *
     * 以节点的id为key, 节点对象为value, 保存到节点池中.
     */
    private function storeNode(BTNode $node)
    {
        $id = $node->getID();
        $this->nodeMap[$id] = $node;
    }

    /**
     * @param $id
     * @return BTNode
     */
    public function getNodeByID($id)
    {
        return isset($this->nodeMap[$id]) ? $this->nodeMap[$id] : null;
    }

    /**
     * @param bool $onlyLeafNode
     *
     * 打印所有节点
     */
    public function dumpNodeMap($onlyLeafNode = false)
    {
        echo '<pre>';
        foreach ($this->nodeMap as $eachNode) {
            if (!$onlyLeafNode || $eachNode->isLeaf) {
                var_dump($eachNode);
            }
        }
    }

    /**
     * 筛选
     */
    public function all() {
        $nodes = $this->rangeFind($this->minNodeID, $this->maxNodeID);
        return  $nodes;
    }
    


}