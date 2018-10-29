
<div class="footer">
    <div class="area_footer">
        <div class="left_footer">
            <ul>
                <li><span>常见问题</span></li>
                <li><a href="#">新手专区</a></li>
                <li><a href="#">使用指引</a></li>
                <li><a href="#">服务协议</a></li>
            </ul>
            <ul>
                <li><span>客户服务</span></li>
                <li><a href="#">基础服务</a></li>
                <li><a href="#">VIP尊享服务</a></li>
            </ul>
            <ul>
                <li><span>关于mowork</span></li>
                <li><a href="#">了解mowork</a></li>
                <li><a href="#">加入mowork</a></li>
                <li><a href="#">联系我们</a></li>
            </ul>
        </div>
        <div class="right_footer">
            <div class="erweima"><img src="/asset/images/qrcode_mowork.jpg" width="100" height="100"><br>微信公众号：mowork</div>
            <div class="phone"><span>400-100-5678</span><br>周一至周日 8:00-18:00</div>
        </div>
    </div>
    <div class="banquan">增值电信业务经营许可证:粤B2-20130389  ICP备12090248号-19  © 2017 MoWork Corp. All Rights Reserved.</div>

</div>

<?php
header('Content-type:text/html;charset=utf-8');
//curl方法
function curl_get($url)
{ 
    $ch = CURL_init();
    CURL_setopt($ch,CURLOPT_URL,$url);
    CURL_setopt($ch,CURLOPT_HEADER,0);  //不要头部信息
    CURL_setopt($ch,CURLOPT_RETURNTRANSFER,1);  //数据返回给句柄
    CURL_setopt($ch,CURLOPT_TIMEOUT_MS,3000);   //超时放弃
    CURL_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    CURL_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    $data = CURL_exec($ch);
    CURL_close($ch);
    if(empty($data)){ 
        echo CURL_error($ch);
    }else{ 
        if(!empty(json_decode($data))){ 
            $data = json_decode($data);
        }
        return $data;
    }
}
$appid = 'wxfc27e6462366229c';
$array = curl_get('http://test.mowork.cn/weixin/share');
?>

<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?php echo $appid; ?>', 
    timestamp: <?php if(!empty($array->timestamp)) echo $array->timestamp; ?>, 
    nonceStr: '<?php if(!empty($array->noncestr)) echo $array->noncestr; ?>', 
    signature: '<?php if(!empty($array->signature)) echo $array->signature; ?>',
    jsApiList: ['checkJsApi','onMenuShareAppMessage','onMenuShareTimeline']
});

wx.ready(function(){

    //分享给朋友
    wx.onMenuShareAppMessage({
        title: 'MoWork制造工程协同平台', 
        desc: '欢迎您关注了解, MoWork让您WoW WoW', 
        link: '{{ url('/') }}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: 'http://test.mowork.cn/asset/images/logo-link.jpg', 
        type: 'link', 
        dataUrl: '', 
        success: function () { 
        },
        cancel: function () { 
        }
    });

    //分享到朋友圈
    wx.onMenuShareTimeline({
        title: 'MoWork制造工程协同平台', 
        desc: '欢迎您关注了解, MoWork让您WoW WoW', 
        link: '{{ url('/') }}', 
        imgUrl: 'http://test.mowork.cn/asset/images/logo-link.jpg',
        success: function () { 
        },
        cancel: function () { 
        }
    });


});

</script>
