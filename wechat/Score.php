<?php
	/**
	* 
	*/
	include_once './TableUser.php';
	class Score 
	{
		public static function findScore($object)
		{
			$user = new Users();
			// $res = $object->FromUserName;
			$res  = $user->getUserAndPassByOpenid($object->FromUserName);
			$img = $user->getImgByOpenid($object->FromUserName);
			if($res==""){
				$content = '未绑定'."\n".'<a href= "http://www.chdbwtx.cn/WDDtest/wechat/bang.php">点击绑定</a>';
			}else{
				$username = $res["username"];
				$password =$res["password"];
				$scoreTitle = Score::getScore($username,$password);

				$url = "http://www.chdbwtx.cn/WDDtest/wechat/detailed_score.php?openid=".$object->FromUserName;
				$url ='👇戳这里查看详细成绩'."\n"."\n".'<a href= "'.$url.'">详细成绩</a>'; 
				//"http://www.chdbwtx.cn/WDDtest/wechat/getOpenid.php?openid=".$object->FromUserName;
			  	$content = $scoreTitle."\n".$url."\n"."\n";
			  	// $content =  array();
			  	// $content[] = array("Title"=>"戳这里查看详细成绩👉","Url"=>$url,"PicUrl"=>"http://www.chdbwtx.cn/allscore/chengji.png","Description"=>"HELLO");
			  	// $content[] = array("Title"=>$scoreTitle,"Url"=>$url,"PicUrl"=>$img,"Description"=>"HELLO");

			}
			return $content;
		}



		public static function getScore($name,$pass)
		{

				$first = array(
						 "学霸，我们做朋友吧",
						 "学无止境，继续努力",
						 "可能这就是大佬吧",
						 "向大佬低头🏆"
				);
				$second = array(
						"书山有路勤为径，学海无涯苦作舟",
						"奋斗，百尺竿头，更进一步",
						"我的未来不是梦"
				);
				$third  = array(
						"努力，一定会有好的收获",
						"相信明天会更好"
				);

				$fourth = array(
						"加油，完成这华丽的逆袭",
						"相信自己，一定可以的"			
				);



			    $jige='及格';
				$lianghao='良好';
				$youxiu='优秀';
			  $set_charset ='export LANG=en_US.UTF-8;';
			  $l=exec("python3 denglu.py $name $pass",$Array,$ret);
			  $l=preg_replace("#\\\u([0-9a-f]+)#ie","iconv('UCS-2BE','UTF-8',pack('H4','\\1'))",$l);
			 // echo $l;
			  $obj= json_decode($l,TRUE);
			  $subject_count = count($obj);
			  $scoreTitle = '🙏🙏🙏'."\n"."\n";
			  $e_score=0;
			  $all_score = 0;
			  $all_temp = 0;
			  for($i = 0;$i<$subject_count;$i++)
			  {
			  	$score = $obj[$i]["zuizhong"];
			  	//成绩
			  	if(preg_match("/{$jige}/",$score)){
					$score = 60;
				}else if(preg_match("/{$lianghao}/",$score)){
					$score = 80;
				}else if(preg_match("/{$youxiu}/",$score)){
					$score = 90;
				}
				$s_score = $obj[$i]["fen"];// 学分
				$mid_score = floatval(trim($score))*floatval(trim($s_score));
				$all_score = $all_score +$mid_score;
				if(floatval(trim($score))!=0){
					$e_score = $e_score + $s_score;
				}
			  	$scoreTitle .='💯';
			  	$scoreTitle .=$obj[$i]["class"];
			  	$scoreTitle =$scoreTitle.'('.$obj[$i]["fen"].')';
			  	$scoreTitle .="\n";
			  	$scoreTitle .= $obj[$i]["zuizhong"];
			  	$scoreTitle .="\n"."\n";

			  }
			  if($e_score==0){
					$aver_score = 0;
				}else{
					$aver_score = sprintf("%.2f",$all_score/$e_score);
				}
			  $scoreTitle .= "加权平均分"."\n".$aver_score."\n";
			  		if ($aver_score>=90) {
				  		$len = count($first);
						$num=mt_rand(0,$len-1);
				  		$scoreTitle .="🌟🌟🌟".$first[$num];
				  	}else if ($aver_score <90 && $aver_score >=75) {
				  		$len = count($second);
						$num=mt_rand(0,$len-1);
				  		$scoreTitle .="🌺🌺🌺".$second[$num];
				  	}else if ($aver_score <75 && $aver_score >=60){
				  		$len = count($third);
						$num=mt_rand(0,$len-1);
				  		$scoreTitle .="🌸🌸🌸".$third[$num];
				  	}else if($aver_score <60 && $aver_score != 0){
				  		$len = count($fourth);
						$num=mt_rand(0,$len-1);
				  		$scoreTitle .="🌱🌱🌱".$fourth[$num];
				  	}
			  
			  	

			  
			  return $scoreTitle;
		}

		public static function jiebang($openid)
		{
			$user = new Users();
			$res =$user->jiebang($openid);
			return '解绑成功'."\n".'<a href= "http://www.chdbwtx.cn/WDDtest/wechat/bang.php">点击绑定</a>';
		}
		
	}
	
?>
