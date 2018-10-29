
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