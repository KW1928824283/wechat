<?php
	include_once './TransmitMsg.php';
	include_once './TableEvent';
    include_once './Score.php';
    include_once './Major.php';
    include_once './Yue.php';
    include_once './Ping.php';
	class RecEvent
	{
		public static function emoji($emoji_str)
{
//将字符串组合成json格式  
$emoji_str = '["'.$emoji_str.'"]'; 
 $emoji_arr = json_decode($emoji_str, true); 
 if (count($emoji_arr) == 1)  
 return $emoji_arr[0];
else 
 return null; 
}
		 //接收事件消息
    public static function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                $content = "❤查成绩的同学回复“成绩”，绑定账号密码即可"."\n"."\n"."❤账号密码为长安大学信息门户的账号和密码，是信息门户，信息门户，信息门户，重要的事情说三遍，账号是学号，密码没有改过的话是身份证后六位"."\n"."\n"."❤评教的话回复“一键评教”即可";
                
                break;
            case "unsubscribe":
                $content = "取消关注";
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "大学物理":
                        $content = "回复“大学物理”查看内容";
                    	// $content = RecEvent::emoji($emoji_str = "\ue415");
                        break;
                    case "饭卡余额":
                        $content = "功能正在开发";
                        break;
                    case "一键评教":
                        $content = Ping::toPing($object->FromUserName);
                        break;
                    case "成绩查询":
                        $content = Score::findScore($object);
                        break;
                    case "长大电话簿":
                        $content = "功能正在开发";
                        break; 
                    case "绑定账号":
                        $content = '<a href= "http://www.chdbwtx.cn/WDDtest/wechat/bang.php">绑定</a>';
                        break;
                    case "解除绑定":
                        $content = Score::jiebang($object->FromUserName);
                        break;
                    case "查校园卡":
                        $content = Yue::getYue($object->FromUserName);
                        break;    
                    default:
                        $content = "点击菜单：".$object->EventKey;
                        break;
                }
                break;
            
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }

        if(is_array($content)){
            
            if (isset($content[0]['PicUrl'])){
                $result =  TransmitMsg::transmitNews($object, $content);
            }
        }else{
            $result = TransmitMsg::transmitText($object, $content);
        }
        return $result;
    }

    


    
		
	}
?>