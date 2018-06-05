<?php
header("Content-type:text/html;charset=utf-8");
     include_once './TableUser.php';
	$openid = $_GET["openid"];
	$a=$_GET["num"];
	$user = new Users();
	$content = $user->getUserAndPassByOpenid($openid);
	$name = $content["username"];
	$pass = $content["password"];
  $set_charset ='export LANG=en_US.UTF-8;';
  $l=exec("python3 card.py $name $pass $a",$Array,$ret);
  $l=preg_replace("#\\\u([0-9a-f]+)#ie","iconv('UCS-2BE','UTF-8',pack('H4','\\1'))",$l);
 // echo $l;
  $obj= json_decode($l,TRUE);
  $count_json = count($obj);
  // var_dump($obj);
?>
<!DOCTYPE html>
<html>
<head>
    <title>校园卡记录</title>
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
                        <td class="text-center">学号</td>
                        <?php $d_xuehao=$obj[$i]['xuhao'];?>
                        <td class="text-center"><?php echo $d_xuehao;?></td>
                        </tr>
                        <tr>
                        <td class="text-center">卡号</td>
                        <?php $d_kahao=$obj[$i]['kahao'];?>
                        <td class="text-center"><?php echo $d_kahao;?></td>
                        </tr>
                        <tr>
                        <td class="text-center">日期</td>
                         <?php  $d_date=$obj[$i]['date'];?>
                        <td class="text-center"><?php echo $d_date;?></td>
                        </tr>
                        </tr>
                        <td class="text-center">时间</td>
                        <?php $d_time=$obj[$i]['time'];?>
                        <td class="text-center"><?php echo $d_time;?></td>
                        </tr>
                        <tr>
                        <td class="text-center">地点</td>
                        <?php  $d_dress=$obj[$i]['didian'];?>
                         <td class="text-center"><?php echo $d_dress;?></td>
                        </tr>
                        <tr>
                        <td class="text-center">金额</td>
                        <?php  $d_money=$obj[$i]['money'];?>
                        <td class="text-center"><?php echo $d_money;?></td>
                        </tr>
                        <tr>
                        <td class="text-center">余额</td>
                        <?php  $d_yue=$obj[$i]['yu_e'];?>
                        <td class="text-center"><?php echo $d_yue;?></td>
                        </tr>
                        <tr align="center">
                        </tr>
                    </table>
                    </div>
            </div>

        </div>

<?php }
?>

 <nav aria-label="...">
            <ul class="pager">
<?php 
       $up = $a-1;
       $down = $a +1;
    $url_up="http://www.chdbwtx.cn/WDDtest/wechat/card.php?openid=".$openid."&num=".$up;
    $url_down="http://www.chdbwtx.cn/WDDtest/wechat/card.php?openid=".$openid."&num=".$down;
	
?>

    <li class="previous"><a href=<?php echo $url_up; ?>><span aria-hidden="true">&larr;</span> 上一页</a></li>
                <li>当前是第<?php echo $a;?>页</li>
                <li class="next"><a href=<?php echo $url_down; ?>>下一页<span aria-hidden="true">&rarr;</span></a></li>
            </ul>
        </nav>
    </div>
</div>
</body>
</html>

	
