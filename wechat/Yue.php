<?php
	/**
	* 
	*/
	header("Content-type:text/html;charset=utf-8");
	include_once './TableUser.php';
	class Yue 
	{
		
		public static function getYue($openid)
		{
			$user = new Users();
			// $res = $object->FromUserName;
			$res  = $user->getUserAndPassByOpenid($openid);
			if($res==""){
				$content = '未绑定'."\n".'<a href= "http://www.chdbwtx.cn/WDDtest/wechat/bang.php">点击绑定</a>';
			}else{
				$Yueurl = "http://www.chdbwtx.cn/WDDtest/wechat/card.php?openid=".$openid."&num=1";
	        	// $content = $Yueurl;
	        	$content = '<a href= "'.$Yueurl.'">点击查看消费记录</a>';
			}
		
		return $content;	
		}

		
	}
?>