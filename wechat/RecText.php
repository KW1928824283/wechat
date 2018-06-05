<?php
	/**
	* 
	*/
    include_once './TableNews.php';
    include_once './TableHalfMap.php';
    include_once './TableMap.php';
    include_once './TableUser.php';
    include_once './TransmitMsg.php';
    include_once './Yue.php';
    include_once './Ping.php';

	class RecText
	{
		public static function receiveText($object)
    
	    {
	        $keyword = trim($object->Content);
	        if(strstr($keyword, "绑定")){
		        $content = '<a href= "http://www.chdbwtx.cn/WDDtest/wechat/bang.php">绑定</a>';
	        }else if(strstr($keyword, "评教")){
	        	// $content = "👇戳这里"."\n".'  <a href= "http://www.chdbwtx.cn/pingjiao/">一键评教</a>  ';
	        	$content = Ping::toPing($object->FromUserName);
	        }else if(strstr($keyword ,"余额")||strstr($keyword ,"校园卡")){
	        	$content = Yue::getYue($object->FromUserName);
	        	// $content = Yue::getYue($object->FromUserName);
	        }else if(strstr($keyword, "音乐")){
	        	$content = "每日一曲";
	        }else if(strstr($keyword, "成绩")){
	        	$content =  Score::findScore($object);
	        	
	        }else if(strstr($keyword, "解绑")){
	        	$content = Score::jiebang($object->FromUserName);
	        }else{
	        	exit;
	        }
	        // else if(strstr($keyword, "大物2")||strstr($keyword, "大学物理2")){
	        // 	$content = RecText::getNewsContent("大物2");
	        // }
	        // else if(strstr($keyword, "大学物理")  ||strstr($keyword, "大物") ||strstr($keyword, "物理")){

	        // 	$content = RecText::getNewsContent("大物1");


	        		// $type = RecText::getType($keyword);


			        // if(trim($type)==""){

			        // 	$newkeyword = RecText::FindHalf($keyword);
			        // 	if($newkeyword ==""){
			        // 		exit;
			        // 	}else{
			        // 		$newtype = RecText::getType($newkeyword);
			        // 		$content = RecText::getContent($newtype,$newkeyword);
			        // 	}


			        // }else{
			        	
			        // 	$content   = RecText::getContent($type,$keyword);
			        // }
	        

	        
	        

	        
	    	
	        
	        //消息的keyword进行数据处理，每个消息的返回值有三种情况

	        // 1.全匹配
	        // 2.半匹配
	        // 3.没有匹配到 
	        // 处理思路：
	        // 1.先从数据库查一次根据消息的keyword值，
	        // 2.如果得到数据，查询结束，执行下面的操作。
	        // 2.如果未得到数据，则为半匹配和没有匹配的结果，需要调用FindHalf方法如果该方法返回值不为空字符串则为半匹配如果为空则说明没有相匹配的。
	        // 4.若为半匹配则再次查询数据库，返回数据。

	        // if(count($res) == 0)
	        // {
	        //     $halfRes = RecText::FindHalf($keyword);
	        //     if($halfRes == "")
	        //     {
	        //     	$content = "博文天下";
	        //     }else{
	        //     	$content = $accountObj->find($halfRes);
	        //     }
	        // }else{
	        //     $content  = $res;
	        // }



	        
	        if(is_array($content)){
	            if (isset($content[0])){
	                $result = TransmitMsg::transmitNews($object, $content);
	            }
	        }else if($content == "每日一曲"){
	        	$result = TransmitMsg::transmitMusic($object); 
	        } else{
	            $result = TransmitMsg::transmitText($object, $content);
	        }
	        return $result;
	    }
	    private static function FindHalf($keyword)
	    {
	    	$mapObj = new HalfMap();
			$return  = "";
			$res = $mapObj->findAllHalf($keyword);
			for($i = 0;$i<count($res);$i++)
			{
				
				if (strstr($keyword, $res[$i]["keyword"])) {
					$return = $res[$i]["pattern"];
					break;
				} 
				
			}
			return $return;

			
		}
		public static function getType($keyword)
		{
			$mapObj = new Map();
			$retrun = "";
			$res =  $mapObj->findType($keyword);
			$return = $res[0]["type"];
			return $return;
		}
		public static function getNewsContent($keyword)
		{
			$accountObj = new Account();
			return $accountObj->find($keyword);
		}

		public static function getContent($type,$keyword)
		{
			//在类型里查到该关键字则表示该类型对应的表一定有数据
	        	switch ($type) {
	        		case 'news':
	        			$content = RecText::getNewsContent($keyword);
	        			
	        			break;
	        		
	        		default:
	        			exit;
	        			break;
	        	}
	        	return $content;
		}
    }
?>