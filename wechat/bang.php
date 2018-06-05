<?php  
      
    if(isset($_SESSION['user'])){  
        print_r($_SESSION['user']);
    exit;
    }
    $APPID='wx5f1ddcf53bfc0365';
    $REDIRECT_URI='http://www.chdbwtx.cn/WDDtest/wechat/callback.php';
    $scope='snsapi_base';
    $scope='snsapi_userinfo';//需要授权
    $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$APPID.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
    
    header("Location:".$url);
    ?>
