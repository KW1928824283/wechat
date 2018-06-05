<?php

$success='
<!DOCTYPE html>
<html>
<head>
	<title>绑定</title>
	<style type="text/css">
		.ch1{
			font-size: 5rem;
			font-family: Microsoft YaHei;
			text-align: center;
			margin-top: 12rem;
		}
		.ch2{
			font-size: 4rem;
			margin-top: 1rem;
		}
		.ch3{
			margin-top: 0rem;
			color:#4F94CD;
		}
	</style>
</head>
<body>
	<div class="ch1">绑定成功</div>
	<div class=" ch1 ch2 ">请在公众号对话框输入</div>
	<div class="ch1 ch3">“成绩”</div>
	<div class=" ch1 ch2">即可查询</div>
</body>
</html>

';
$fail = '<!DOCTYPE html>
<html>
<head>
	<title>绑定</title>
	<style type="text/css">
		.ch1{
			font-size: 5rem;
			font-family: Microsoft YaHei;
			text-align: center;
			margin-top: 15rem;
		}
		
	</style>
</head>
<body>
	<div class="ch1">绑定失败或已绑定</div>
</body>
</html>';
include_once './TableUser.php';
$name=$_POST['name'];
$pass=$_POST['pass'];
session_start();
$user_obj=$_SESSION['user'];
// echo $user_obj;
// var_dump($user_obj);
$user = new Users();
$res  = $user->bangding($user_obj,$name,$pass);
if($res == 1) echo $success;
else echo $fail;
?>
