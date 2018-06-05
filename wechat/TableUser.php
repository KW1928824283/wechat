<?php
include_once './database.php';

include_once './TransmitMsg.php';
// var_dump($Obj->find());
class Users extends Database
{
	function __construct()
	{
		if (!$this->getConnect()) {
			echo "connect error";
			exit;
		}
	}
	public function getUserAndPassByOpenid($openid)
	{
		$sql = "SELECT username,password FROM users WHERE openid = '$openid'";
		$return = $this->query($sql);
		if(count($return)==0)return "";
		else return $return[0];
		

	}
	public function getImgByOpenid($openid)
	{
		$sql = "SELECT headimgurl FROM users WHERE openid = '$openid'";
		$return = $this->query($sql);
		return $return[0]["headimgurl"];
	}
	public function bangding($user_obj,$name,$pass)
	{
		// var_dump($user_obj);
		// echo $name;
		// echo $pass;
		$openid = $user_obj["openid"];

		$nickname = $user_obj["nickname"];
		$city = $user_obj["city"];
		$headimgurl = $user_obj["headimgurl"];
		$province = $user_obj["province"];
		$sex = $user_obj["sex"];
		$contry = $user_obj["country"];

		$sql = "INSERT INTO `users` (`openid`, `nickname`, `sex`, `province`, `city`, `country`, `headimgurl`, `username`, `password`) VALUES ('$openid', '$nickname', $sex, '$province', '$city', '$contry', '$headimgurl', '$name', '$pass')";
		
        $return = $this->execute($sql);
		echo $return;
		// $t = $this->errorInfo($return);
		// echo $t;
		 //if($this->isError($return))
		// {
		 //	echo "Y";
		// }
		//var_dump($this->errorInfo($return));
        //if($return===false){
          //  $errMS=$this->errorInfo();
           // echo'错误码：'.$errMS[0].'<br/>'.'错误编号:'.$errMS[1].'<br/>'.'错误信息'.$errMS[2].'<br/>';

        //}

		
		return $return;

	}
	public function jiebang($openid)
	{
		$sql = "DELETE FROM users WHERE openid='$openid'";
		$retrun = $this->execute($sql);
		return $return;
	}
}
// $user = new Users();
// $res = $user->getImgByOpenid("oErKPv5jKXjPD9N4OHd59uENNe4c");
// echo $res;
?>
