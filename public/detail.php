<?php

    date_default_timezone_set('PRC');
    $url = $_SERVER['REQUEST_URI'];
    $urlArr = explode('?', $url);
    $tmpUrl = isset($urlArr[1]) ? '?'.$urlArr[1] : '';
    
    $url = 'http://www.webs-soft.com/mw_getNewlist/test.php'.$tmpUrl;
    

    $result = file_get_contents($url);

    $result = json_decode($result,true);
    
    $data = $result['data'];
    $last = $result['last'];
    $next = $result['next'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name=”viewport” content=”width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes” />
    <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <!-- Bootstrap core CSS -->
    <title> Mowork-加入我们</title>
    <link href="http://www.motooling.com/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://www.motooling.com/css/common.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>-->

    <link href="http://www.motooling.com/css/about.css" rel="stylesheet">
    <style type="text/css">
        .h1, .h2, .h3, h1, h2, h3 {
            margin-top:0px;
        }
        .article-content {
            width: 1000px;
        }
        .article-content p {
            text-indent: 0px;
        }

        .line {
            margin: 0;
            padding: 0;
            margin-top: 95px;
        }
        .list-media {
            list-style: none;
            padding: 0;
            padding-bottom: 60px;
        }
        .article-media li {
            padding: 13px 0 15px;
        }
        .article-media li {
            padding: 13px 0 15px;
        }
        .article-media li {
            margin-bottom: 25px;
        }
        .media:first-child {
            margin-top: 0;
        }
        .media, .media-body {
            overflow: hidden;
            zoom: 1;
        }
        .media.media-x .float-left {
            margin-right: 60px;
        }
        .float-left {
            float: left!important;
        }
        .article-media .media .caldata .content {
            padding: 20px;
            background: #fff;
            text-align: center;
            margin-right: 8px;
            color: #416eb6;
            width: auto;
            border: 1px solid #416eb6;

        }
        .text-center {
            text-align: center;
        }
        .article-media .media .caldata .content p.day {
            font-size: 60px;
            color: #416eb6;
            font-weight: bold;
            padding-bottom: 25px;
        }
        .article-media .media .caldata .content p {
            text-align: center;
            text-indent: 0px;
        }
        .media p {
            margin: 5px 0 0 0;
            text-align: justify;
            line-height: 18px;
        }
        .article-media .media .caldata .content p.mon-year {
            font-size: 24px;
            padding-top: 15px;
            border-top: 1px solid #416eb6;
        }
        .media.media-x .media-body {
            margin: 0;
        }
        .article-media .media-body h3 {
            font-size: 16px;
            margin-bottom: 5px;
            margin-top: 10px;
        }
        .article-media .media-body h3 a {
            color: #5a5a5a;
            font-size: 16px;
            line-height: 100%;
            color: #282828;
            font-size: 30px;

        }
        .article-media .media-body h3 a:hover {
            text-decoration:none;
        }
        .article-media .media-body p {
            color: #787878;
            line-height: 34px;
            font-size: 17px;
            padding-top: 10px;
            letter-spacing: 0px;
        }
        .pagess {
            clear: both;
            margin: 20px;
            overflow: hidden;
            margin-left: 0px;
            text-align: center;
            font-size: 12px;
            margin-bottom: 105px;
        }
        .pagess ul li {
            display: inline-block;
            border: 1px solid #ccc;
            padding: 2px 9px;
            margin: 0 3px;
            line-height: 20px;
            background: #fff;
            color: #999;
            width: 40px;
            height: 40px;
            line-height: 35px;
        }
        .pagess ul li:first-child,.pagess ul li:last-child {
            width: 100px;
        }
        .pagess ul li.thisclass {
            display: inline-block;
            border: 1px solid #ccc;
            padding: 2px 9px;
            margin: 0 3px;
            background: #416eb6;
            color: #fff;
        }

        .article{
            text-align: center;
        }
        .article-intro{
            text-align: right;
            padding-top: 35px;
        }
        .article-intro span:not(last-child){
            margin-right: 5px;
        }
        .article-img{
            margin-top: 60px;
        }

        /*.article-img img{*/
            /*width: 700px;*/
            /*height: 270px;*/
        /*}*/

        .article-intro span{
            color: #d9d9d9;
        }
        .article-content1{
            color: #787878;
            margin-top: 60px;
            font-size: 20px;
            text-align: left;
            letter-spacing: 1px;
        }
        .pagess span{
            color: #d9d9d9;
            display: inline-block;
        }
        .pagess span:first-child{
            float: left;
        }
        .pagess span:last-child{
            float: right;
        }
        .article h3{
            border-bottom: 1px solid #d9d9d9;
            padding-bottom: 40px;
            padding-top: 80px;
        }
        .pagess {
            margin-top: 150px;
        }
        .pagess span{
            color: #d9d9d9;
            font-size: 20px;
        }
        .pagess span a{
            color: #d9d9d9;
            font-size: 18px;
            text-decoration: none;
        }
        .pagess span a:hover{
            text-decoration: none;
        }
    </style>
</head>
<body style="min-width: 1300px;overflow-x: hidden">
<header>

</header>
<div class="navbar-wrapper">
    <div class="container">
        <nav class="navbar  navbar-static-top">
            <div class="container">
                <div class="navbar-header ">
                    <a class="navbar-brand" href="#"><img src="http://www.motooling.com/img/logo.png"></a>
                    <ul class="nav navbar-nav">
                        <li><a href="http://www.mowork.cn">MoWork</a></li>
                        <li><a href="http://www.motooling.com">MoTooling</a></li>
                        <li><a href="../en/about.html">关于我们</a></li>
                        <li><a href="javascript:alert('功能正在完善中')">知乎</a></li>
                        <li><a href="javascript:alert('功能正在完善中')">公司动态</a></li>
                        <li><a href="#footer">联系我们</a></li>
                        <li><a href="#">加入我们</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">中文 <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="http://www.mowork.cn/en/articles/1511272533390.html">英文</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
<section class="article-content content">
    <div class="article">
        <h3><?php echo $data['title']; ?></h3>
        <div class="article-intro"><span>日期:</span><span style="margin-right: 20px;"><?php echo date('Y-m-d', $data['inputtime']); ?></span><span>浏览:</span><span>360</span></div>
<!--        --><?php //if(!empty($data['litpic'])) { ?>
<!--        <div class="article-img">-->
<!--            <img src="http://www.webs-soft.com/--><?php //echo $data['litpic']; ?><!--"/>-->
<!--        </div>-->
<!--        --><?php //} ?>
        <div class="article-content1">
            <?php echo $data['content']?>
        </div>
    </div>
    <div class="pagess">
        <span><?php if(!empty($last)){ ?><a href="/detail.php?id=<?php echo $last['id']; ?>">上一篇：<?php echo $last['title']; } ?></a></span>
        <span><?php if(!empty($next)){ ?><a href="/detail.php?id=<?php echo $next['id']; ?>">下一篇：<?php echo $next['title']; } ?></a></span>
    </div>
</section>

<div id="float" class="float-on">

    <div class="arrow-off" id="arrow-off">
        <img src="http://www.motooling.com/img/float/arrow-off.png" alt="">
    </div>
    <div class="arrow-on" id="arrow-on">
        <img src="http://www.motooling.com/img/float/arrow-on.png" alt="">
    </div>
    <ul class="qrcode" >
        <li>
            <img src="http://www.motooling.com/img/float/mw.png" alt="" width="100" >
            <p>MoWork公众号</p>
        </li>
        <li>
            <img src="http://www.motooling.com/img/float/mt.png" alt="" width="100">
            <p>MoTooling公众号</p>
        </li>
    </ul>
</div>
<div class="footer" id="footer">
    <div class="content">
        <div class="contact">
            <p class="phone">0755-26718823</p>
            <p class="work-time">周一至周五 08:00-17:30</p>
            <br>
            <ul class="qrcodes">
                <li>
                    <img src="http://www.motooling.com/img/footer/1.png" width="100">
                    <p>MoWork公众号</p>
                </li>
                <li>
                    <img src="http://www.motooling.com/img/float/mt.png" alt=""  width="100">
                    <p>MoTooling公众号</p>
                </li>
            </ul>
        </div>

        <div class="address">
            <p class="company">深圳市伟博思技术有限公司</p>
            <br>
            <p> 深圳市南山区粤海街道粤兴三道9号</p>
            <p> 华中科技大学深圳产学研基地A座A1104-A1106号</p>
            <br>
            <p>联系邮箱：<a href="mailto:support@mowork.cn">support@mowork.cn</a></p>
            <br>
            <br>
            <p> <a style="text-decoration:underline" target="_blank" href="http://www.miitbeian.gov.cn">粤ICP备1709237号</a></p>
        </div>
    </div>

</div>
<script src="../js/jquery.mini.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script>
    document.querySelector('#arrow-off').addEventListener('click',function () {
        document.querySelector('#float').setAttribute('class','float-on')
    })
    document.querySelector('#arrow-on').addEventListener('click',function () {
        document.querySelector('#float').setAttribute('class','float-off')
    })
</script>
</body>
</html>

