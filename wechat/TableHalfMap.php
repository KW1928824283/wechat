<?php

include_once './database.php';



class HalfMap extends Database
{
	
	function __construct()
	{
		if (!$this->getConnect()) {
			echo "connect error";
			exit;
		}
	}


	
	public function findTypeAndHalf($keyword)
	{
		$sql = "SELECT * FROM halfmap WHERE keyword LIKE '%$keyword%'";

		$return = $this->query($sql);
		return $return;
	}
	public function findAllHalf()
	{
		$sql = "SELECT keyword , pattern FROM halfmap";
		$return = $this->query($sql);
		return $return;
	}

	/**
	 * save info in db
	 * @param  string $phoneno 
	 * @param  string $passwd  
	 * @return boolean          
	 */
	public function saveUserInfo($phoneno, $passwd)
	{
		$sql = "INSERT INTO userbasicinfo(ubi_PhoneNumber, ubi_Passwd) VALUES($phoneno, $passwd)";

		$retBasic = $this->execute($sql);

		$sql = "INSERT INTO userdetailinfo(udi_PhoneNumber) VALUES($phoneno)";

		$retDetail = $this->execute($sql);
		
		if ($retBasic && $retDetail) {
			return true;
		} else {
			return false;
		}

		// retrun (($retBasic && $retDetail) ? true : false);
	}

	/**
	 * delete user info
	 * @param  string $phoneno 
	 * @return void          
	 */
	public function deleteUserInfo($phoneno)
	{
		// delete userbasicinfo item
		$sql = "DELETE FROM userbasicinfo WHERE ubi_PhoneNumber = $phoneno";
		$this->execute($sql);

		// delete userdetailinfo item
		$sql = "DELETE FROM userdetailinfo WHERE udi_PhoneNumber = $phoneno";
		$this->execute($sql);

		// delete sharebook items
		$sql = "DELETE FROM sharebook WHERE sb_SelfPhoneNumber = $phoneno";
		$this->execute($sql);

		// delete borrowbook items
		$sql = "DELETE FROM borrowbook WHERE bb_SelfPhoneNumber = $phoneno";
		$this->execute($sql);
	}

	/**
	 * create assoc tables about user
	 * @param  string $phoneno 
	 * @return boolean          
	 */
	public function createAssocTables($phoneno)
	{
		$sql = "CREATE TABLE " . $phoneno . "friendlist(fl_PhoneNumber int(11),
			fl_RemarkName varchar(32), 
			PRIMARY KEY (fl_PhoneNumber))";

		$retFriendList = $this->execute($sql);

		$sql = "CREATE TABLE " . $phoneno . "sharebook(
			sb_Id int(11) NOT NULL AUTO_INCREMENT, 
			sb_BookName varchar(56) NOT NULL,
			sb_Img varchar(56) NOT NULL,
			sb_Introduction text, 
			sb_Tags varchar(52), 
			sb_Author varchar(32), 
			sb_Press  varchar(32), 
			sb_Price varchar(32),
			sb_Count int(5) NOT NULL DEFAULT '1',
			sb_Loan  tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (sb_Id))";

		$retShareBook = $this->execute($sql);

		$sql = "CREATE TABLE " . $phoneno . "borrowbook(
			bb_Id int(11) NOT NULL AUTO_INCREMENT, 
			bb_BookName varchar(56) NOT NULL, 
			bb_OwnerNumber int(11) NOT NULL, 
			bb_InTime varchar(24) NOT NULL, 
			bb_OutTime varchar(24) NOT NULL, 
			bb_Img  varchar(56) NOT NULL, 
			bb_Introduction  text, 
			bb_Tags varchar(52) NOT NULL, 
			bb_Author varchar(32), 
			bb_Press varchar(32), 
			bb_Price varchar(32), 
			PRIMARY KEY (bb_Id))";

		$retBorrowBook = $this->execute($sql);

		// can not check return value for they are all 0
		return true;
	}

	/**
	 * drop assoc tables
	 * @param  string $phoneno 
	 * @return void          
	 */
	public function dropAssocTables($phoneno)
	{
		$sql = "DROP TABLE " . $phoneno . "friendlist";
		$this->execute($sql);

		$sql = "DROP TABLE " . $phoneno . "sharebook";
		$this->execute($sql);

		$sql = "DROP TABLE " . $phoneno . "borrowbook";
		$this->execute($sql);		
	}


	/**
	 * check passwd valid
	 * @param  string $phoneno 
	 * @param  string $passwd  
	 * @return boolean          
	 */
	public function checkPasswd($phoneno, $passwd)
	{
		$sql = "SELECT ubi_Passwd AS passwd FROM userbasicinfo WHERE ubi_PhoneNumber = $phoneno";
		$return = $this->query($sql);
		return ($return[0]["passwd"] == $passwd);
	}
	public function checkOpenid($phoneno,$openid)
	{
		$sql = "SELECT ubi_Openid AS openid FROM userbasicinfo WHERE ubi_PhoneNumber = $phoneno";
		$return = $this->query($sql);
		return ($return[0]["openid"] == $openid);
	}


	public function changeUserPasswd($phoneno, $passwd)
	{
		$sql = "UPDATE userbasicinfo SET ubi_Passwd = '$passwd' WHERE ubi_PhoneNumber = $phoneno";
		$return = $this->execute($sql);

		return $return ? true : false;
	}
	public function findOpenid($friendPhoneNo)
	{
		$sql = "SELECT ubi_Openid AS openid FROM userbasicinfo WHERE ubi_PhoneNumber = $friendPhoneNo";
		$return = $this->query($sql);
		return $return[0]["openid"];
	}
	public function updateOpenid($phoneno,$openid)
	{
		$sql = "UPDATE userbasicinfo SET ubi_Openid = '$openid' WHERE ubi_PhoneNumber = $phoneno";
		$return = $this->execute($sql);

		return $return ? true : false;
	}
	public function clearOpenid($phoneno)
	{
		$sql = "UPDATE userbasicinfo SET ubi_Openid = '' WHERE ubi_PhoneNumber = $phoneno";
		$return = $this->execute($sql);
		return $return ? true :false;
	}

	public function searchFriend($selfPhoneNo, $friendPhoneNo)
	{
		$sql = "SELECT ubi_PhoneNumber AS friendphoneno, ubi_Openid AS openid  FROM userbasicinfo WHERE ubi_PhoneNumber LIKE '$friendPhoneNo%' LIMIT 5";
		$friendArray = $this->query($sql);
		
		return $friendArray;
	}
	public function finFriend($selfPhoneNo, $friendPhoneNo)
	{
		$sql = "SELECT ubi_PhoneNumber AS phoneno FROM userbasicinfo WHERE ubi_PhoneNumber = $selfPhoneNo";
	}


}
 
?>