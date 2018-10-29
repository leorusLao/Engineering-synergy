<div class="area_top">
    <div class="top">
        <div class="left_top">MoWork：最好的制造工程协同平台</div>
        <div class="right_top">
            <div class="denglu"><a href="/login">登录</a></div>
            <span class="sep">|</span>
            <div class="zhuce"><a href="/signup">注册</a></div>
            <div class="lang" id="lang">{{Lang::get('mowork.lang')}}<i class="icon-caret-down"></i></div>
        </div>
    </div>
</div>

<div class="area_nav">
    <div class="cent_nav">
        <div class="logo"><a href="/"><img src="/asset/images/logo.png" style="height:60px;width:auto"></a></div>

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
                        <li><a href="/">首 页</a></li>
                        <li><a href="#">解决方案</a></li>
                        <li><a href="#">合作伙伴</a></li>
                        <li><a href="#">关于我们</a></li>
                        <li><a href="#">新闻博客</a></li>
                    </ul>
                </div>

            </div>
        </nav>

        <div class="sel_lang">
            <div class="zwen"><a href="/select-lang/zh-cn">中文</a></div>
            <div class="ywen"><a href="/select-lang/en">English</a></div>
        </div>
        <div class="area_search">
                <div class="area_text"><input type="text" /></div>
                <div class="btn_search"><a href="#"><i class="icon-search"></i></a></div>
                <div class="btn_alert">{{Lang::get('mowork.keyword')}}</div>
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
