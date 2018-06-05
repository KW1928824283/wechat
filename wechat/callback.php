<?php

            $appid = "wx5f1ddcf53bfc0365";
            $secret = "0fbb8c69191d3ed9525fc023e9542cc4";
            // $appid = "wx5f1ddcf53bfc0365";  
            // $secret = "0fbb8c69191d3ed9525fc023e9542cc4";
            $code = $_GET["code"];  
    $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$get_token_url);  
    curl_setopt($ch,CURLOPT_HEADER,0);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );  
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);  
    $res = curl_exec($ch);  
    curl_close($ch);  
    $json_obj = json_decode($res,true);  
    //根据openid和access_token查询用户信息  
    $access_token = $json_obj['access_token'];  
    $openid = $json_obj['openid'];  
    $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';  
      
    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$get_user_info_url);  
    curl_setopt($ch,CURLOPT_HEADER,0);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );  
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);  
    $res = curl_exec($ch);  
    curl_close($ch);  
      
    //解析json  
    $user_obj = json_decode($res,true);  
    // var_dump($user_obj);
	session_start();
    $_SESSION['user'] = $user_obj;  
    // var_dump($user_obj);
$outpout ='<html>
<head>
    <title>用户绑定</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1.0" />
    <link rel="stylesheet" href="./bang/css/animate.css"/>
    <link rel="stylesheet" href="./bang/css/bootstrap-theme.css"/>
    <link rel="stylesheet" href="./bang/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="./bang/css/style.css">
</head>
<body>
<!-- header开始 -->
<div class="header animated bounceInDown"></div>
<!-- header结束 -->
<!-- content开始 -->
<div class="container animated pulse">
    <form action="sql.php" class="form" method="post">
        <div class="form-group">
            <label>学号</label>
            <input type="text" name="name" class="form-control" placeholder="请输入您的学号">
        </div>


        <div class="form-group">
            <label>密码</label>
            <input type="password" name="pass" class="form-control" placeholder="请输入您的密码">
        </div>
       <div class="animated bounceInLeft form-group">
           <input type="submit" class="btn btn-block btn-info"  value="点击绑定"/>
       </div>
    </form>
</div>
<!-- content结束 -->

</body>
</html>';	
      echo $outpout;
    




    ?>  

