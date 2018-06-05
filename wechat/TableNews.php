<?php

include_once './database.php';


// var_dump($Obj->find());

class Account extends Database
{
	
	function __construct()
	{
		if (!$this->getConnect()) {
			echo "connect error";
			exit;
		}
	}


	
	public function find($keyword)
	{
		$sql = "SELECT title AS Title , description AS Description , url AS Url , pic_url AS PicUrl FROM news WHERE keyword = '$keyword'";

		$return = $this->query($sql);
		return $return;
		// return $sql;
	}

	

}
 // $Obj = new Account();
 // $res =  $Obj->find("201");

 // var_dump($res);
?>