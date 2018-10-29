<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>MoWork</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <link href="/asset/css/bootstrap.css" type="text/css" rel="stylesheet" />
    <script src="/asset/js/jquery-3.2.1.min.js"></script>
    <script src="/asset/js/bootstrap.min.js"></script>
    <link href="/asset/css/style.css" type="text/css" rel="stylesheet" />
    <link href="/asset/css/index.css" type="text/css" rel="stylesheet" />
    <link href="/asset/css/font-awesome.min.css" type="text/css" rel="stylesheet" >
</head>
<body>

<div class="area_top">
    <div class="top">
        <div class="left_top">MoWork：北半球最好的制造工程协同平台</div>
        <div class="right_top">
            <div class="denglu"><a href="/login">登录</a></div>
            <span class="sep">|</span>
            <div class="zhuce"><a href="//www.mowork.cn/signup">注册</a></div>
            <div class="lang">中文版 <i class="icon-caret-down"></i></div>
        </div>
    </div>
</div>

<div class="area_nav">
    <div class="cent_nav">
        <div class="logo"><img src="/asset/images/logo.jpg"></div>

        <nav class="area_dh navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target="#example-navbar-collapse">
                        <span class="sr-only">切换导航</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="example-navbar-collapse">
                    <ul class="nav navbar-nav" role="navigation">
                        <li class="active"><a href="#">首 页</a></li>
                        <li><a href="#">智能硬件</a></li>
                        <li><a href="#">可穿戴设备</a></li>
                        <li><a href="#">航天军工</a></li>
                        <li><a href="#">人工智能</a></li>
                    </ul>
                </div>

            </div>
        </nav>

        <div class="sel_lang">
            <div class="zwen"><a href="#">中文</a></div>
            <div class="ywen"><a href="#">ENG</a></div>
        </div>
        <div class="area_search">
            <div class="area_text"><input type="text" /></div>
            <div class="btn_search"><a href="#"><i class="icon-search"></i></a></div>
            <div class="btn_alert"><a href="#">智能硬件</a><a href="#">智能家居</a></div>
        </div>

    </div>
</div>


<script>
    $(document).ready(function(){
        $('.lang').click(
            function(){
                $('.sel_lang').toggle();
            }
        )
        $('.sel_lang').click(
            function(){
                $('.sel_lang').css('display','none');
            }
        )
        $('.zwen').click(function(){
            $('.sel_lang').css('display','none');
        })
        $('.ywen').click(function(){
            $('.sel_lang').css('display','none');
        })
        $('.area_search input').focus(
            function(){
                $('.btn_alert').css('display','none');
            }
        )
        $('.area_search input').blur(
            function(){
                content = $('.area_search input').val();
                if(content.length <= 0){
                    $('.btn_alert').css('display','block');
                }
            }
        )
    })
</script>