<?php

	header("Content-type:text/html;charset=utf-8");
	include_once './TableUser.php';
	$user = new Users();
	$openid = $_GET["openid"];
	$res  = $user->getUserAndPassByOpenid($openid);
	$name=$res['username'];
    $pass=$res['password'];
  $set_charset ='export LANG=en_US.UTF-8;';
  $l=exec("python3 pingjiao.py $name $pass",$Array,$ret);
  $l=preg_replace("#\\\u([0-9a-f]+)#ie","iconv('UCS-2BE','UTF-8',pack('H4','\\1'))",$l);
 // echo $l;
  $obj= json_decode($l,TRUE);
  $count_json = count($obj);
  // var_dump($obj);
?>

  <!DOCTYPE html>
<html>
<head>
    <title>评教</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.css">
</head>
<body>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"></h3>
    </div>
    <div class="panel-body">

    </div>
</div>
<div class="media">
    
    <div class="media-body">

<?php
  for ($i=0;$i<$count_json;$i++){
?>

        <div class="panel panel-primary">

            <div class="panel-body">
                <div class="table-responsive">

                    <table class="table table-hover">
                        <tr>
                        <td class="text-center">课程名</td>
                        <?php $d_xuehao=$obj[$i]['kecheng'];?>
                        <td class="text-center"><?php echo $d_xuehao;?></td>
                        </tr>
                        <tr>
                        <td class="text-center">教师</td>
                        <?php $d_kahao=$obj[$i]['teacher'];?>
                        <td class="text-center"><?php echo $d_kahao;?></td>
                        </tr>
                        <tr>
                        <td class="text-center">结果</td>
                         <?php  $d_date=$obj[$i]['status'];?>
                        <td class="text-center"><?php echo $d_date;?></td>
                        </tr>
                        <tr align="center">
                        </tr>
                    </table>
                    </div>
            </div>

        </div>

<?php }
?>
    </div>
</div>
</body>
</html>
