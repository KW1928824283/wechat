<?php
	$appid = "XXXXXXXXXXXXXXXX"; 
	$appsecret = "XXXXXXXXXXXXXXXXXXXXXXXXXXXxxxx"; 
	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret"; 
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$output = curl_exec($ch); 
	curl_close($ch); 
	$jsoninfo = json_decode($output, true); 
	$access_token = $jsoninfo["access_token"];



	$jsonmenu = '{ 
	  "button":[ 
	  { 
	   "type":"view",
	   "name":"信息门户直通车", 
	   "url":"http://ids.chd.edu.cn/authserver/login?service=http%3A%2F%2Fportal.chd.edu.cn%2F"
	  }, 
	  
	  {
	   "name":"查准考证",
	   "sub_button":[
	   		{
	   			"type":"click",
	   			"name":"准考证存储",
	   			"key":"存储信息"
	   		},
	   		{
	   			"type":"click",
	   			"name":"准考证删除",
	   			"key":"删除信息"
	   		},
	   		{
	   			"type":"click",
	   			"name":"准考证查询",
	   			"key":"四六级查询"
	   		}
	   ]}
	  ] 
	}'; 
	$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token; 
	$result = https_request($url, $jsonmenu); 
	var_dump($result); 
	  
	function https_request($url,$data = null){ 
	 $curl = curl_init(); 
	 curl_setopt($curl, CURLOPT_URL, $url); 
	 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
	 curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
	 if (!empty($data)){ 
	  curl_setopt($curl, CURLOPT_POST, 1); 
	  curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
	 } 
	 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
	 $output = curl_exec($curl); 
	 curl_close($curl); 
	 return $output; 
	}

?>