<?php

namespace App\Service;

use Throwable;
use Workerman\Worker;

class WsService
{

    // WS端口
    private $port = '23001';

    // 全部连接 - 仅用于单线程模式
    private $allConnect = [];

    public function __construct($port = "23001")
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

    private function start()
    {
        // 创建一个Worker监听20002端口，不使用任何应用层协议
        $this->server = new Worker("websocket://0.0.0.0:" . $this->port);
        // 启动4个进程对外提供服务
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

    public function onConnect($connection) {
        $connection->id = rand_str(8);
        $this->allConnect[$connection->id] = $connection;
        try {
            $events = Event::query()->where('deleted_at', 'is', null)->where('status', 'in', [1,2])
            ->orWhere([['status', '=', '3'],['solve_at', '>', date('Y-m-d H:i:s', strtotime('-1 days')) ]])
            ->limit(15)->order('created_at', 'DESC')->get();
            $this->response($connection, 0, 0, $events->toArray());
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }

    public function onMessage($connection, $data) {
        $json = json_decode($data, true);
        if (!isset($json['type'])) {
            return false;
        }
        switch ($json['type']) {
            case "act":
                $this->act($json['act'], $json['data'], $json['qid'], $connection);
                break;
        }
    }

    function act($act, $data, $qid, $connection) {
        switch ($act) {
            case "add":
                try {
                    $event = Event::query()->create($data);
                } catch(Throwable $t) {
                }
                if ($event) {
                    $this->broadcast('add', $event->toArray(), $connection->id);
                    $this->response($connection, $qid, 0, $event->toArray());
                } else {
                    $this->response($connection, $qid, 500, null, "添加失败，请重试");
                }
                break;
            case "edit":
                try {
                    $id = $data['id'];
                    $event = Event::query()->find($id);
                    if ($data['status'] == 3 && $event->status != 3) {
                        $data['solve_at'] = date('Y-m-d H:i:s');
                    }
                    $change = Event::query()->where('id', $id)->update($data);
                } catch(Throwable $t) {
                }
                if ($change) {
                    $event = Event::query()->find($id);
                    $this->broadcast($act, $event->toArray(), $connection->id);
                    $this->response($connection, $qid, 0, $event->toArray());
                } else {
                    $this->response($connection, $qid, 500, null, "修改失败或无变动");
                }
                break;
            case "del":
                try {
                    $id = $data['id'];
                    $change = Event::query()->where('id', $id)->update([
                        'deleted_at' => date('Y-m-d H:i:s')
                    ]);
                } catch(Throwable $t) {
                }
                if ($change) {
                    $this->broadcast($act, ['id' => $id], $connection->id);
                    $this->response($connection, $qid, 0, ['id' => $id]);
                } else {
                    $this->response($connection, 500, null, "删除失败或无变动");
                }
                break;
            case "query":
                try {
                    $limit = $data['limit'] ?: 10;
                    $page = $data['page'] ?: 1;
                    $order = $data['order'] ?: "created_at";
                    $orderType = $data['orderType'] ?: "DESC";
                    $events = Event::query()->where('deleted_at', 'is', null)->where($data['query'] || [])->limit($limit,($page -1) * $limit)->order($order, $orderType)->get();
                    $this->response($connection, $qid,  0, $events->toArray());
                } catch(Throwable $t) {
                    $this->response($connection, $qid,  500, [], "查询失败");
                }
                
                break;
        }

    }

    private function broadcast($type, $data, $exceptCid) {
        $json = [
            "type" => $type,
            'code' => "0",
            "data" => $data,
            "msg" => "OK",
        ];
        $jsonStr = json_encode($json);
        foreach($this->allConnect as $cid => $cn) {
            if ($cid != $exceptCid) {
                try {
                    $cn->send($jsonStr);//发送消息
                } catch(\Exception $e) {

                }
            }
        }
    }

    private function response($cn, $qid, $code, $data, $msg = "OK") {
        $json = [
            "type" => "response",
            'code' => $code,
            'qid'  => $qid,
            "data" => $data,
            "msg"  => $msg,
        ];
        $cn->send(json_encode($json));//发送消息
    }

    public function onWorkerStart($connection) {

    }

    public function onClose($connection) {
        unset($this->allConnect[$connection->id]);
    }
}