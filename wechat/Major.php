<?php
	/**
	* 
	*/
	class Major 
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
	}
?>