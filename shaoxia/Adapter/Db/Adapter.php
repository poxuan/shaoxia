<?php

namespace Shaoxia\Adapter\Db;

/**
 * 适配器通用接口
 */
interface Adapter {
    public static function isAvailable();

    public function connect($config);

    public function getVersion();

    public function query($sql, $bindings = []);

    public static function parseSql(Query $query);

    public function fetch($resource);

    public function fetchAll($resource);

    public function lastInsertID();
}