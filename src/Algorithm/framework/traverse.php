<?php

/**
 * 遍历框架
 */

// 遍历数组
function traverse_array($arr) {
    for($i = 0; $i< count($arr); $i++) {

    }
}

// 遍历列表-非递归
function traverse_list1($head) {
    for($i = $head; $i != null; $i = $i->next()) {

    }
}

// 遍历列表-递归
function traverse_list2($head) {
    // 
    traverse_list2($head->next());
}

// 遍历二叉树
function traverse_tree($head) {
    // 前序
    traverse_tree($head->left);
    // 中序
    traverse_tree($head->right);
    // 后序
}

// 遍历多叉树
function traverse_ntree($head) {
    foreach($head->children as $i) {
        // 前序
        traverse_ntree($i);
        // 后序
    }
}