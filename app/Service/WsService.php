<?php

namespace App\Service;

use Throwable;
use Workerman\Worker;

class WsService
{

    const DEFAULT_PORT = 23001;
    // WS端口
    private $port;

    // 全部连接 - 仅用于单线程模式
    private $allConnect = [];

    public function __construct($port = self::DEFAULT_PORT)
    {
        $this->port = $port;
    }

    // 开始
    public function run($act) {
        switch($act) {
            case 'start':
                $this->start();
                break;
            case 'stop':
                $this->stop();
                break;
        }
    }

    // 结束
    public function stop()
    {
        Worker::stopAll();
        echo "worker stoped \n";
    }

    // 启动
    private function start()
    {
        // 创建一个Worker监听20002端口，不使用任何应用层协议
        $this->server = new Worker("websocket://0.0.0.0:" . $this->port);
        // 启动4个进程对外提供服务, !!! windows 下没用
        $this->server->count = 4;
        // 连接时回调
        $this->server->onConnect = [$this, 'onConnect'];
        // 收到客户端信息时回调
        $this->server->onMessage = [$this, 'onMessage'];
        // 进程启动后的回调
        $this->server->onWorkerStart = [$this, 'onWorkerStart'];
        // 断开时触发的回调
        $this->server->onClose = [$this, 'onClose'];
        // 运行worker
        Worker::runAll();
        echo "worker started \n";
    }

    // 连接成功
    public function onConnect($connection) {
        $connection->id = time() . randStr(8);
        $this->allConnect[$connection->id] = $connection;
    }

    // 收到消息
    public function onMessage($connection, $data) {
        $json = json_decode($data, true);
        if (!isset($json['type'])) {
            return false;
        }
        switch ($json['type']) {
            case "act":
                $this->action($connection, $json['act'], $json['data'], $json['qid']);
                break;
            case "heart":
                $this->responseOk($connection, 'heart');
                break;
        }
    }

    // 开始
    public function onWorkerStart($connection) {

    }

    // 关闭
    public function onClose($connection) {
        unset($this->allConnect[$connection->id]);
    }

    // 行为处理
    private function action($cn, $act, $data, $qid) {
        switch ($act) {
            case "add":
                break;
            case "edit":
                break;
            case "del":
                break;
            case "query":
                break;
        }
    }

    // 广播
    public function broadcast($type, $data, $msg, $exceptCids = []) {
        $json = [
            "type" => $type,
            'code' => 0,
            "data" => $data,
            "msg" => $msg,
        ];
        $jsonStr = json_encode($json);
        foreach($this->allConnect as $cid => $cn) {
            if (!in_array($cid, $exceptCids)) {
                try {
                    $cn->send($jsonStr);//发送消息
                } catch(\Exception $e) {

                }
            }
        }
    }

    /**
     * 成功响应
     */
    private function responseOk($cn, $type, $data = [], $qid = 0, $msg = "OK") {
        $json = [
            "type" => $type, // 类型
            'code' => 0, // 返回码 0 正常 其他异常
            'qid'  => $qid,  // 请求ID
            "msg"  => $msg,  // 信息
            "data" => $data, // 数据
        ];
        $cn->send(json_encode($json));//发送消息
    }

    /**
     * 失败响应
     */
    private function responseFail($cn, $type, $msg, $qid = 0, $code = 500) {
        $json = [
            "type" => $type, // 类型
            'code' => $code, // 返回码 0 正常 其他异常
            'qid'  => $qid,  // 请求ID
            "msg"  => $msg,  // 信息
        ];
        $cn->send(json_encode($json));//发送消息
    }
}