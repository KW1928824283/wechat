<?php
	/**
	* 
	*/
	header("Content-type:text/html;charset=utf-8");
	include_once './TableUser.php';
	class Ping
	{
		
		public static function toPing($openid)
		{
			$user = new Users();
			// $res = $object->FromUserName;
			$res  = $user->getUserAndPassByOpenid($openid);
			if($res==""){
				$content = '未绑定'."\n".'<a href= "http://www.chdbwtx.cn/WDDtest/wechat/bang.php">点击绑定</a>';
			}else{
				// $name = $res["username"];
				// $pass = $res["password"];
				// $set_charset ='export LANG=en_US.UTF-8;';
				// $l=exec("python3 pingjiao.py $name $pass",$Array,$ret);
				// $l=preg_replace("#\\\u([0-9a-f]+)#ie","iconv('UCS-2BE','UTF-8',pack('H4','\\1'))",$l);
				//  // echo $l;
				// $obj= json_decode($l,TRUE);
				// $count_json = count($obj);
				// $content = "一键评教成功";

				$Pingurl = "http://www.chdbwtx.cn/WDDtest/wechat/pingjiao.php?openid=".$openid;
	        
	        	$content = '<a href= "'.$Pingurl.'">点击评教</a>';
			}
		
		return $content;	
		}

		
	}
?>