<?php

require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
use Workerman\WebServer;
use PHPSocketIO\SocketIO;

// 创建socket.io服务端，监听2021端口
$io = new SocketIO ( 2050 );
// 当有客户端连接时打印一行文字
$io->on('connection',function($socket)use($io){
  // 定义message事件回调函数
    $socket->on('message', function($jsonData)use($io){
        if ($jsonData['type'] == 'login'){
            $arr = array(
                "status" => "success",
                "type" => "login",
                "content" => "welcome " . $jsonData ['name'] 
            );
            $io->emit('server',json_encode($arr));
            //$io->emit('server',(object)$arr);
        }else if($jsonData['type'] == 'say'){
            $content = $jsonData['name'] . " " . $jsonData['content'];
            $time = $jsonData['time'];
            $timestamp = $jsonData['timestamp'];
            $arr = array(
                "status" => "success",
                "type" => "say",
                "content" => $content,
                "time" => $time,
                "timestamp" => $timestamp
            );
            $io->emit('server',json_encode($arr));
            //$io->emit('server',(object)$arr);
        }else if($jsonData['type'] == 'push'){
            $content = $jsonData['content'];
            $time = date("Y-m-d H:i",time());
            $timestamp = date("Y-m-d H:i",time());
            $arr = array(
                "status" => "success",
                "type" => "push",
                "content" => $content,
                "time" => $time,
                "timestamp" => $timestamp
            );
            $io->emit('server',json_encode($arr));
            //$io->emit('server',(object)$arr);
        }
    });
  
});

// 执行监听
$io->on('workerStart',function(){
    // 监听一个http端口
    $inner_http_worker = new Worker('text://0.0.0.0:2051');
    // 当http客户端发来数据时触发
    $inner_http_worker->onMessage = function($http_connection,$data){
        global $io;
        $jsonData = json_decode($data,true);
        $arr = array(
            "status" => "success",
            "type" => "push",
            "content" => $jsonData['message']['msg_content'],
            "time" => time(),
            "timestamp" => time()
        );
        $io->emit('server',json_encode($arr));
        //$io->emit('server',(object)$arr);

        $io->emit('server',json_encode($jsonData));
        //$io->emit('server',(object)$jsonData);
        
        return $http_connection->send('success');
    };
    // 执行监听
    $inner_http_worker->listen();
});

if(!defined('GLOBAL_START')){
    Worker::runAll();
}