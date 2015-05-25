<?php 	
function lifecycle($method, $params) { 
    switch($params[0]) { 
        default: 
            $reply = 'You post string is '.$params[0]; 
    } 
    return $reply; 
} 

$server = xmlrpc_server_create(); // 产生一个XML-RPC的服务器端

$func_name_client = 'cycle';
$func_name_server = 'lifecycle';
xmlrpc_server_register_method($server, $func_name_client, $func_name_server); // 注册一个供RPC客户端调用的名称: cycle
$request = $HTTP_RAW_POST_DATA; // 接收RPC客户端传递过来的数据

// 调用RPC服务器的处理函数
$response = xmlrpc_server_call_method($server, $request, null); 

// 返回结果给RPC客户端 
header('Content-Type: text/xml'); 
echo $response; 

//销毁XML-RPC服务器端资源 
xmlrpc_server_destroy($server); 
