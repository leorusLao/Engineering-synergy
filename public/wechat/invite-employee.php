<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>员工注册邀请</title>
 
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
 

    <!-- Demo styles -->
    <style>
    html, body {
        height: 100%;
    }
    body {
        background: #fff;
        font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
        font-size: 14px;
        color:#000;
        margin: 0;
        padding: 0;
    }
    .swiper-container {
        width: 100%;
        height: 100%;
        position:absolute;
    }
    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        /* Center slide text vertically */
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
    }
.mbox{
position: relative;
float: right;
margin-right:2px;
top: 10px;
z-index:1000;

} 
img {
        width:100%;
        height:100%;
        max-width:640px;
    }
.music{width:2.5rem;height:2.5rem;position:absolute;float:right;right:.5rem;top:.5rem;z-index:100}.music .control{width:2.5rem;height:2.5rem;background:url(http://oss.szzbmy.com/rp2/apps/static/widget/music/music_c0fda01.gif) transparent no-repeat center center;background-size:contain}.music .control .control-after{width:1.5rem;height:1.5rem;position:absolute;top:50%;left:50%;float:right;margin-top:-.75rem;margin-left:-.75rem;background-size:100% 100%;-webkit-animation:rotate2d 1.2s linear infinite;animation:rotate2d 1.2s linear infinite;}.music.stopped .control{background:0 0}
    .rotating {
-webkit-animation: spin 0.7s infinite linear;
-moz-animation: spin 0.7s infinite linear;
-o-animation: spin 0.7s infinite linear;
-ms-animation: spin 0.7s infinite linear;
}
@-webkit-keyframes spin {
0% { -webkit-transform: rotate(0deg);}
100% { -webkit-transform: rotate(360deg);}
}
@-moz-keyframes spin {
0% { -moz-transform: rotate(0deg);}
100% { -moz-transform: rotate(360deg);}
}
@-o-keyframes spin {
0% { -o-transform: rotate(0deg);}
100% { -o-transform: rotate(360deg);}
}
@-ms-keyframes spin {
0% { -ms-transform: rotate(0deg);}
100% { -ms-transform: rotate(360deg);}
}

.slide-bg {
    background-repeat: no-repeat;
    background-size: contain;
    background-position: left top;
    max-width:640px;
    width: 100%;
    height: auto;
    margin: 0 auto;
}



</style>
</head>
<body> 
<?php
include "./wechat-sdk/wechat.class.php";
$uid = $_REQUEST['uid'];
$companyId = $_REQUEST['companyId'];
$companyName = $_REQUEST['companyName'];

$options = array(
		'token'=> 'testtokenmowork',
 		'encodingaeskey'=>'PnqeUvdyPgWC5gC7tHndLDe2AOOkGaGUcevwxE45RCT', 
		'appid'=> 'wxfc27e6462366229c',  
        'appsecret'=> '3b166209a2e76bf7a298be1afebcd7d7',
        'debug' => true
	);
	
$weObj = new Wechat($options);
$access_token = $weObj->getAccessToken(); 
$signPackage = $weObj->getJsSign('http://www.mowork.cn/wechat/invite-employee.php', 0, '', 'wxfc27e6462366229c')
 
?>


<div style="margin-top:50px"><?php echo $companyName;?>邀请员工注册  
<a href="http://test.mowork.cn/login/<?php echo $uid; ?>/<?php echo $companyId;?>">去注册</a> 
</div>
<script type="text/javascript" src="http://test.mowork.cn/asset/js/jquery-1.11.3.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" ></script>
<script>
  
  wx.config({
    debug: false,//debug: true,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
        'checkJsApi',
   
        'onMenuShareAppMessage'
  
     ]
  });
 
</script>

<script>
 
 
wx.ready(function(){
 
	wx.onMenuShareAppMessage({
        title:  '<?php echo $companyName;?>邀请员工注册', // 分享标题
        desc: "分享描述邀请员工注册邀请员工注册", // 分享描述
        link: "http://www.mowork.cn/wechat/invite-employee.php?uid=<?php echo $uid; ?>&companyId=<?php echo $companyId;?>&companyName=<?php echo $companyName;?>",
        imgUrl: 'http://test.mowork.cn/asset/images/logo-link.jpg', // 分享图标
        type: 'link', // 分享类型,music、video或link，不填默认为link
        success: function () { 
			alert('success');
            // 用户确认分享后执行的回调函数

        },
        fail： function () { 
			alert('failed');
            // 用户确认分享后执行的回调函数

        },
	}); 


	wx.error(function(res){
		alert('error:' + res);
	} 

	
});
</script>
 
</body>
</html>
