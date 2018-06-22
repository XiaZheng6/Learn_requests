<?php
// 指明给谁推送，为空表示向所有在线用户推送
$to_uid = '';
// 推送的url地址，上线时改成自己的服务器地址
$push_api_url = "http://socket.speed01.xyz/api.php";
$post_data = array(
   'platform' => 'all',
   'audience' => 'all',
   'msg_content' => '消息内容本身',
   'content_type' => 'text',//消息内容类型  text文本，img图片，link链接
   'title' => '消息标题',
   'type' => 'select'
);
$ch = curl_init ();
curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
curl_setopt ( $ch, CURLOPT_POST, 1 );
curl_setopt ( $ch, CURLOPT_HEADER, 0 );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
$return = curl_exec ( $ch );
curl_close ( $ch );
var_export($return);