<?php
	include_once './database.php';
class Map extends Database
{

	function __construct()
	{
		if (!$this->getConnect()) {
			echo "connect error";
			exit;
		}
	}

	public function findType($keyword)
	{
		$sql = "SELECT type FROM map WHERE keyword = '$keyword'";

		$return = $this->query($sql);
		return $return;
	}
}
?>