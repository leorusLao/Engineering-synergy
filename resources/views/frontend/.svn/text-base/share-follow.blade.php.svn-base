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
<li>基于世界领先的人工智能平台 </li>
</ul>
</div>

@stop

@section('main-body')
<div class="area_cpjj">
<div class="title_cpjj">产品简介</div>

 
</div>
<div class="text-center" id='share'></div>
<div class="text-center" style="margin-bottom: 20px"><b>扫一扫,邀请朋友关注</b></div>
@stop

@section('footer.append')

<script type="text/javascript" src="/asset/js/jquery-qrcode-0.14.0.min.js">
 </script>
<script type="text/javascript">
$(document).ready(function() {
	$('#share').qrcode({ "render": 'canvas',
		 "size": 200,
		 "mode": 0,
		 "text": "{{ url('/') }}"
    });
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

