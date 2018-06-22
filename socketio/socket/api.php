<?php

$platform = $_POST['platform']?$_POST['platform']:'all';
$audience = $_POST['audience']?$_POST['audience']:'all';
$msg_data = $_POST['msg_data']?$_POST['msg_data']:'';
$msg_content = $_POST['msg_content']?$_POST['msg_content']:'';
$content_type = $_POST['content_type']?$_POST['content_type']:'text';
$title = $_POST['title']?$_POST['title']:'';
$type = $_POST['type']?$_POST['type']:'select';
$timestamp = $_POST['timestamp']?$_POST['timestamp']:time();
$cid = rand(10000,99999);

// if($message == '' || $message['msg_content'] == ''){
// 	echo "error";
// }

// 建立socket连接到内部推送端口
// $client = stream_socket_client('tcp://43.254.88.175:2051', $errno, $errmsg, 1);
$client = stream_socket_client('tcp://192.168.1.121:2051', $errno, $errmsg, 1);
// 推送的数据，包含uid字段，表示是给这个uid推送
$msg_data = json_decode($msg_data,true);
$msg_data = $msg_data ? $msg_data : (object)[];
$data = array(
    'platform'=>$platform,
    'audience'=>$audience,
    'timestamp'=>$timestamp,
    'message'=>array(
        'msg_content'=>$msg_content,
        'msg_data'=>$msg_data,
        'content_type'=>$content_type,
        'title'=>$title,
        'type'=>$type
    ),
    'cid'=>$cid
);

// 发送数据，注意5678端口是Text协议的端口，Text协议需要在数据末尾加上换行符
fwrite($client, json_encode($data)."\n");
// 读取推送结果
echo fread($client, 8192);
