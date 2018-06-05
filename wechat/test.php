<?php
	include_once './TableMap.php';
	$mapObj = new Map();
	$keyword = "大物";
	$return  = "";
	$res = $mapObj->findType($keyword);
	echo $res[0]["type"];
	// for($i = 0;$i<count($res);$i++)
	// {
		
	// 	if (strstr($keyword, $res[$i]["keyword"])) {
	// 		$return = $res[$i]["pattern"];
	// 		break;
	// 	} 
		
	// }
	// echo $return;
?>