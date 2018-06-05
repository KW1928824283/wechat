<?php
header("Content-type:text/html;charset=utf-8");
    include_once './TableUser.php';
	$openid = $_GET["openid"];
	$user = new Users();
	$content = $user->getUserAndPassByOpenid($openid);
	$name = $content["username"];
	$pass = $content["password"];
  $set_charset ='export LANG=en_US.UTF-8;';
  $l=exec("python3 denglu.py $name $pass",$Array,$ret);
  $l=preg_replace("#\\\u([0-9a-f]+)#ie","iconv('UCS-2BE','UTF-8',pack('H4','\\1'))",$l);
 // echo $l;
  $obj= json_decode($l,TRUE);
  $count_json = count($obj);
  // var_dump($obj);
?>
<!DOCTYPE html>
<html>
<head>
    <title> 成绩查询</title>
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
                        <?php $d_xuehao=$obj[$i]['class'];?>
                        <td class="text-center"><?php echo $d_xuehao;?></td>
                        </tr>
                        <tr>
                        <td class="text-center">学分</td>
                        <?php $d_kahao=$obj[$i]['fen'];?>
                        <td class="text-center"><?php echo $d_kahao;?></td>
                        </tr>
                        <tr>
                        <td class="text-center">期末成绩</td>
                         <?php  $d_date=$obj[$i]['qimo'];?>
                        <td class="text-center"><?php echo $d_date;?></td>
                        </tr>
                        </tr>
                        <td class="text-center">平时成绩</td>
                        <?php $d_time=$obj[$i]['pingshi'];?>
                        <td class="text-center"><?php echo $d_time;?></td>
                        </tr>
                        <tr>
                        <td class="text-center">最终成绩</td>
                        <?php  $d_dress=$obj[$i]['zuizhong'];?>
                         <td class="text-center"><?php echo $d_dress;?></td>
                        </tr>
                        <tr>
                        <td class="text-center">绩点</td>
                        <?php  $d_money=$obj[$i]['jidian'];?>
                        <td class="text-center"><?php echo $d_money;?></td>
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

	
