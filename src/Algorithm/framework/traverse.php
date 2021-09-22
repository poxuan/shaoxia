<?php


function traverse_array($arr) {
    for($i = 0; $i< count($arr); $i++) {

    }
}

function traverse_list1($head) {
    for($i = $head; $i != null; $i = $i->next()) {

    }
}

function traverse_list2($head) {
    // 
    traverse_list2($head->next());
}


function traverse_tree($head) {
    // 前序
    traverse_tree($head->left);
    // 中序
    traverse_tree($head->right);
    // 后序
}

function traverse_ntree($head) {
    foreach($head->children as $i) {
        // 前序
        traverse_ntree($i);
        // 后序
    }
}