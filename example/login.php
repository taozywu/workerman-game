<?php

require_once 'pb_proto_cmd.php';
require_once 'pb_proto_result.php';


// 建立与服务端的链接
$socket = stream_socket_client("tcp://192.168.1.248:8490", $err_no, $err_msg);
if(!$socket)
{
   exit($err_msg);
}

// 设置为阻塞模式
stream_set_blocking($socket, true);

$cmd = new Cmd();

//登录
//$cmd->setObj('\Server\Member');
//$cmd->setMethod('authLogin');
//$cmd->appendParams('moxiaobai');
//$cmd->appendParams('rxg622124');

//在线数据
$cmd->setObj('\Server\Online');
$cmd->setMethod('addUserOnline');
$cmd->appendParams('1');
$cmd->appendParams('爱琴海');
$cmd->appendParams('13392');

//$cmd->setMethod('deleteUserOnline');
//$cmd->appendParams('1');
//$cmd->appendParams('13392');

$buffer = $cmd->SerializeToString();
$total_length = 4 + strlen($buffer);
$buffer = pack('N', $total_length) . $buffer;

stream_socket_sendto($socket, $buffer);

// 读取服务端返回的数据
$result =  stream_socket_recvfrom($socket, 65535);
$result = substr($result, 4);


$rs = new Result();

try {
	$rs->parseFromString($result);
} catch (Exception $e) {
   exit($e->getMessage());
}

$rs->dump();

// 关闭链接
fclose($socket);
