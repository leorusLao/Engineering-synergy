@extends('front-base')

@section('front-hotpic')

	<div class="area_hotpic">
		<div class="hotpic">
			<li class="natic"><a href="#"><img src="/asset/images/hotpic1.jpg"></a></li>
			<li><a href="#"><img src="/asset/images/hotpic2.jpg"></a></li>
			<li><a href="#"><img src="/asset/images/hotpic3.jpg"></a></li>
			<div class="left_btn"><a href="javascript:void(0);"></a></div>
			<div class="right_btn"><a href="javascript:void(0);"></a></div>
		</div>
		<ul class="font_hotpic">
			<li class="natic">基于世界领先的人工智能平台111111</li>
			<li>基于世界领先的人工智能平台22</li>
			<li>基于世界领先的人工智能平台33</li>
		</ul>
	</div>

@stop

@section('main-body')
	<div class="area_cpjj">
		<div class="title_cpjj">产品简介</div>
		<p class="cont_cpjj">嘿动是全球最便捷的短流程协作平台，无论是微信、QQ、微博、论坛，还是其他更新的社交网络，通过嘿动，您的信息都将以最醒目方式展示、传播！我们支持发布包括产品创新，创业招募，艺术表演，社区动员，论坛讲座，研讨会，社团活动，公益活动，展会沙龙等各类型的线下活动。</p>
		<div class="bg_cpjj"><img src="/asset/images/bg_gsjs.png"></div>
	</div>

	<div class="gngs">
		<div class="area_gngs">
			<div class="title_cpjj">功能概览</div>
			<ul class="cont_gngs">
				<li class="one_gngs fl"><span class='fB'>「 大数据 」</span><br>
					我们海量的活动数据，以及庞大的用户关联让嘿动充满了智慧。活动怎样更有效果，看看后台的分析，您就能知道。</li>
				<li class="one_gngs fr"><span class='fB'>「 大数据 」</span><br>
					我们海量的活动数据，以及庞大的用户关联让嘿动充满了智慧。活动怎样更有效果，看看后台的分析，您就能知道。</li>
				<li class="one_gngs fl"><span class='fB'>「 大数据 」</span><br>
					我们海量的活动数据，以及庞大的用户关联让嘿动充满了智慧。活动怎样更有效果，看看后台的分析，您就能知道。</li>
				<li class="one_gngs fr"><span class='fB'>「 大数据 」</span><br>
					我们海量的活动数据，以及庞大的用户关联让嘿动充满了智慧。活动怎样更有效果，看看后台的分析，您就能知道。</li>
			</ul>
		</div>
	</div>

	<div class="hzhb">
		<div class="title_cpjj">合作伙伴</div>
		<ul class="cont_cpjj height_hzhb">
			<li><a href="#" target="_blank"><img src="/asset/images/logo_hzhb.jpg"></a></li>
			<li><a href="#" target="_blank"><img src="/asset/images/logo_hzhb.jpg"></a></li>
			<li><a href="#" target="_blank"><img src="/asset/images/logo_hzhb.jpg"></a></li>
			<li><a href="#" target="_blank"><img src="/asset/images/logo_hzhb.jpg"></a></li>
			<li><a href="#" target="_blank"><img src="/asset/images/logo_hzhb.jpg"></a></li>
			<li><a href="#" target="_blank"><img src="/asset/images/logo_hzhb.jpg"></a></li>
			<li><a href="#" target="_blank"><img src="/asset/images/logo_hzhb.jpg"></a></li>
			<li><a href="#" target="_blank"><img src="/asset/images/logo_hzhb.jpg"></a></li>
			<li><a href="#" target="_blank"><img src="/asset/images/logo_hzhb.jpg"></a></li>
			<li><a href="#" target="_blank"><img src="/asset/images/logo_hzhb.jpg"></a></li>
		</ul>
	</div>
@stop

@section('footer-append')

<script type="text/javascript">
    $(document).ready(function() {
        num_hotpic = 0;
        $('.left_btn a').click(
            function () {
                if (num_hotpic == 0) {
                    num_hotpic = 2;
                } else {
                    num_hotpic--;
                }
                $('.hotpic li').removeClass('natic');
                $('.hotpic li').eq(num_hotpic).addClass('natic');
                $('.font_hotpic li').removeClass('natic');
                $('.font_hotpic li').eq(num_hotpic).addClass('natic');
            }
        )
        $('.right_btn a').click(
            function () {
                if (num_hotpic == 2) {
                    num_hotpic = 0;
                } else {
                    num_hotpic++;
                }
                $('.hotpic li').removeClass('natic');
                $('.hotpic li').eq(num_hotpic).addClass('natic');
                $('.font_hotpic li').removeClass('natic');
                $('.font_hotpic li').eq(num_hotpic).addClass('natic');
            }
        )
    })
</script>

@stop
