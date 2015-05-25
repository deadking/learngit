<?php 
function xmlrpc_call($host, $port, $server_file, $request) { 

    //打开指定的服务器端
    $fp = fsockopen($host, $port, $errno, $errstr); 
	
    //构造需要进行通信的XML-RPC服务器端的查询POST请求信息
    $query  = "POST ".$server_file." HTTP/1.0\n";
	$query .= "User_Agent: My Egg Client\n";
	$query .= "Host: ".$host."\n";
	$query .= "Content-Type: text/xml\n";
	$query .= "Content-Length: ".strlen($request)."\n\n"; // 必须是两个 \n !!
	$query .= $request."\n"; 
	
    //把构造好的HTTP协议发送给服务器，失败返回false
    if (!fputs($fp, $query, strlen($query))) { 
        $errstr = "Write error"; 
        return 0; 
    } 
	
    //获取从服务器端返回的所有信息，包括HTTP头和XML信息
    $contents = ''; 
    while (!feof($fp)) { 
        $contents .= fgets($fp); 
    } 
	
    //关闭连接资源后返回获取的内容
    fclose($fp); 
	
	// 分析从服务器端返回的XML，去掉HTTP头信息，并且把XML转为PHP能识别的字符串
	$split = '<?xml version="1.0" encoding="iso-8859-1"?>';
	$xml = explode($split, $contents);
	$xml = $split . array_pop($xml);
	$contents = xmlrpc_decode($xml);

    return $contents;

} 

////////////////////////////////////////////////////////////

//构造连接RPC服务器端的信息
$host = 'localhost'; 	// PRC服务器主机地址
$port = 80; 			// PRC服务器主机端口
$server_file = '/test/rpc_server.php'; // 左斜杠不能少!!
$request = xmlrpc_encode_request('cycle', 'egg'); // 把需要发送的XML请求进行编码: 调用的方法是cycle, 参数是egg

//调用 xmlrpc_call 函数把所有请求发送给XML-RPC服务器端, 并获取返回值
$response = xmlrpc_call($host, $port, $server_file, $request);
print_r($response);
