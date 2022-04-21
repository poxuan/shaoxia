<?php

namespace Shaoxia\Adapter;

/**
 * 适配器通用接口
 */
interface DbAdapter {

    // 判断是否可用,组件是否已安装
    public static function isAvailable();

    // 是否支持事务
    public static function isSupportTransaction();

    // 开始事务
    public function beginTransaction();

    // 回滚事务
    public function rollback();

    // 提交事务
    public function commit();

    // 尝试连接到服务器
    public function connect($config);

    // 获取服务版本
    public function getVersion();

    // 构造请求
    public function query($sql, $bindings = []);

    // 生成sql
    public static function parseSql(Query $query);

    // 获取一条记录
    public function fetch($resource);

    // 获取全部记录
    public function fetchAll($resource);

    // 获取影响行数
    public function rowCount($resource);

    // 获取上一次插入的ID
    public function lastInsertID();
}