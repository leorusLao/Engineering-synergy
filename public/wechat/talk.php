<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Wechat Backend</title>
 
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

#page2 {
    background-image:url(PPT-Meeting-Bali-1.jpg); 
}


    </style>
</head>
<body> 
<?php
include "./wechat-sdk/wechat.class.php";
//include "Db.class.php";
 
$options = array(
		'token'=> 'testtokenmowork',
 		'encodingaeskey'=>'PnqeUvdyPgWC5gC7tHndLDe2AOOkGaGUcevwxE45RCT', 
		'appid'=> 'wxfc27e6462366229c',  
        'appsecret'=> '3b166209a2e76bf7a298be1afebcd7d7',
        'debug' => true
	);
	
$weObj = new Wechat($options);
$access_token = $weObj->getAccessToken(); 
 
//$signPackage = $weObj->getJsSign('', 0, '', 'wxfc27e6462366229c');
 
$type = $weObj->getRev()->getRevType();
 
switch($type) {//initiate from wechat server(/wechat client)
	case Wechat::MSGTYPE_TEXT:
			$input = $weObj->getRevContent();
			$openid = $weObj->getRevFrom();
			$usr = $weObj->getUserInfo($openid);
			 
			$username = '';		 
			if($usr)$username = $usr['nickname'];
			
			$http_text = '';
			if(strstr($input, "http:") || strstr($input, "https:")){
				 
				$newsData = array(array('Title'=>'您转发的链接',  'Description'=>'', 'PicUrl'=>'http://365jia.cn/uploads/13/0301/5130c2ff93618.jpg', 'Url' =>$input));
			 
				$weObj->news($newsData)->reply(); //$result = $weObj->transmitNews($newsData);//echo $result;
			 	 
				$input = addslashes($input);
				$md5 = md5($input);
				
				$db = new Db();
				//check if this input has done before
				$count = $db->row("SELECT * FROM wechat WHERE md5=:f LIMIT 1",array("f" => $md5),PDO::FETCH_NUM);
				if(!$count){
					$page = get_web_page($input); 
				    $content = $page['content'];
					$content = str_replace('data-src','src',$content);
					$content = addslashes($content);
					if(stristr($content,'charset=gb2312')){ 
						$content = iconv("gb2312", "utf-8", $content);
					}
					$openid = $weObj->getRevFrom();
					//$usr = $weObj->getUserInfo($openid);
			 
					//$username = '';		 
					//if($usr)$username = $usr['nickname'];
					
					$db->query("INSERT INTO wechat(url,md5,open_id,lang_code,content,created_at,updated_at) VALUES('$input', '$md5','$openid','zh','$content',now(),now()) ");
				}
				 
				exit;
			}
			else if(mb_stristr("巴厘岛年会ME活动巴厘岛旅游巴厘岛免费旅游",$input)){
				$news =  array(
				"0"=>array(
				'Title'=>'免费参加巴厘岛旅游的答案',
				'Description'=> "您好,".$username."!\n\n".
				"很高兴您对我们巴厘岛免费旅游感兴趣。 只要您购买或者销售我们的网站推广工具套餐，累计金额达到9万美金，可以获得一个巴厘岛年会5天4晚全免名额；累计金额达到6.3万美金，自费机票，公司承担酒店食宿费用；累计金额达到3.6万美金，自费机票＋236美金团费，其余由公司承担； 累计金额达到2.7万，自费机票＋330美金团费，其余由公司承担。 欢迎您加入我们ME新媒体商圈大家庭。 请输入“购买网站推广套餐”，获得更多咨询，或者请上ME官网www.memyth.cn(www.memyth.com)查询。谢谢您！",
				'PicUrl'=>'http://mmbiz.qpic.cn/mmbiz/ibtR2faqBicZeksoMCRlXz3LwaibftTG7pgQMCPuicPpXWGjrnbjoUY2losJ3C31ruJSdHpiblwZaaRhplPBWltJ6uw/640?wx_fmt=jpeg&tp=webp&wxfrom=5',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=208626177&idx=1&sn=cc8bfb886aad95601079d5d2052dba75#wechat_redirect'
				)
				);
				$weObj->transmitNews($news);
			}
			else if(mb_stristr("购买网站推广套餐工具购买套餐",$input)){
				$news =  array(
				"0"=>array(
				'Title'=>'购买网站推广工具套餐',
				'Description'=> "您好,".$username."!\n\n".
				"欢迎您来到ME新媒体新商业圈子里。 ME网站推广工具套餐可以帮助您的企业网站迅速提升真实IP访问量，迅速提升您的网站国内国际排名，迅速提高您网站的搜索曝光率，被搜索时出现在各大搜索引擎的前三页甚至首页，直接提高您的全球销量和知名度。首先请观看ME官网www.memyth.cn(www.memyth.com)/商家成功案例视频， 然后在“我要推广”栏目里面选购“网站推广工具套餐”。 或者咨询您身边的ME代理商。谢谢您！祝您生意兴隆！",
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZeibrDOJL9JGZicx9n76BRSgLjn2St3ykweXzyQWsmQiacPlEkFLXeKRRqSMBNJ8jzy3IEYJB763QlBw/0?wx_fmt=jpeg',
				'Url'=>'https://www.memyth.com/advertising'
				)
				);
				$weObj->transmitNews($news);
			}
			else if(strstr($input, "ppt")){
				$news =  array(
				"0"=>array(
				'Title'=>'【M.E活动】阳春三月带上我爱的人一起飞',
				'Description'=> "阳春三月带上我爱的人一起飞",
				'PicUrl'=>'http://w.memyth.cn/annual/PPT-Meeting-Bali-1.jpg',
				'Url'=>'http://w.memyth.cn/annual/index.html'
				)
				);
				$weObj->transmitNews($news);
				
			}
			else if(strstr($input, "更早")){
				$news2 = array(
			    "16" => array(
				'Title'=>'【M.E绿生活】英国11岁少年超有头脑：开网店年入约63万元',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZfsj7bbQxfwMxxNmty7rEXlXpRo1eKc9EdtvJ9aP9fDFMwem0BQCpaibKC37WEb4xMRWlkcpHGHZkA/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400312983&idx=1&sn=bd17fc5030076ae41a1e0befca89bf91#rd'
				),
				"17" => array(
				'Title'=>'【M.E绿生活】加拿大值得代购的十大商品',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZdib2IpKdjeOIn9YTibYbAiaYav1kp4VPRFGx87Fyfic5f53mfeO6vPBjCFINvLoOkk3K7bKPHCl5rWug/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400262927&idx=1&sn=04c631217fc04683bb2ec72de3d9bb9d#rd'
				),
				
				"18" => array(
				'Title'=>'【 M.E活动 】就是要免费环游世界 ——阳春三月，带上我爱的人一起飞',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZdEf5yZnunflDuBhblWHdd2VYdbkEdpF4zkubRZQwiccIQlte2ibXKpWSajZUMuiaPRfbjxU9SVO0ibyg/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=208626177&idx=1&sn=cc8bfb886aad95601079d5d2052dba75#wechat_redirect'
				),
				
				"19" => array(
				'Title'=>'【 M.E视频 】《温哥华女人》第二集，第三集新鲜出炉，为您讲述不一',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZeHgNmqRicFFWb02WqEh3HvNF4sFxnjotLxQY26LP3FIe3Xk91y7reZLSHPMphISKZQaZTnTjCgv5g/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=208582760&idx=1&sn=358b8d18429499ed02942f620981c631#rd'
				),
				 
				"20" => array(
				'Title'=>'【M.E绿生活】国庆献礼：加拿大知名品牌普润施白黎芦醇买10送1 祝福全球华人青春永驻 健康长寿10月01日',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZdcbbibBKGIDo374Lp341Yia6odL8Xw6wNlsXPFyicJU0eVibfokcriahxoiaMSNwVfjUICz1M2s2ia8O6Bw/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=208534787&idx=1&sn=c3773e40e80a013ef30cb216e2459d74#rd'
				),
				
				"21" => array(
				'Title'=>'【M.E分享】一个温哥华BCIT的毕业生，24岁一个人做了一个网站，36岁',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZe3DSLskyJ21iaOaXdyza8KEuoovDSZnA2jV2KAicFAxmnM3ia1V0d8lI5DqJh73x1psObbncEiavkxiaw/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=207711254&idx=1&sn=d5e8f276dd789bab26c24db2ce7a78d5#rd'
				)
			 	 
				
				);
			
			 
				$weObj->transmitNews($news2);
			
				
				
			}
			
			 
			
			$weObj->text("您好,".$username."!\n\n您的留言我们已经收到了，客服部会尽快与您联系的，祝您开心快乐每一天！😃" )->reply();
			exit;
			break;
 
	case Wechat::MSGTYPE_IMAGE:
			$input = $weObj->getRevContent();
			$openid = $weObj->getRevFrom();
			$usr = $weObj->getUserInfo($openid);
			$username = '';		 
			if($usr)$username = $usr['nickname'];
		    //$weObj->text("hello, Image Sent".$weObj->getRevPic())->reply();
			$weObj->text("您好,".$username)->reply();
			break;
	case Wechat::MSGTYPE_VOICE:
	 		$voc = $weObj->getRevVoice();
		    $weObj->text("hello, Voice Received;".$voc['mediaid'].','.$voc['format'])->reply();
			break;
	case Wechat::MSGTYPE_LINK:
		    $weObj->text("hello, Link Sent")->reply();
			break;
	case Wechat::MSGTYPE_LOCATION:
	        $loc = $weObj->getRevGeo();
	        $info = '纬度:'.$loc['x'].';经度:'.$loc['y'].';大概地址:'.$loc['label'];//todo send my location info to a subscriber
		    $weObj->text("Hello, Location Gotten: ". $info)->reply();
			break;
    case Wechat::MSGTYPE_EVENT:
		  
		 $eventkey = $weObj->getRevEvent();
		 $input = $weObj->getRevContent();
		 $openid = $weObj->getRevFrom();
		 $usr = $weObj->getUserInfo($openid);
			 
		 $username = '';		 
		 if($usr)$username = $usr['nickname'];
		  
		 
		 if($eventkey['key'] == 'EVENT_JOIN'){
			$news =  array(
				"0"=>array(
				'Title'=>'[M.E商机] M.E多媒体事业最完整介绍版!每天全球各地上百人加入！M.E就这么任性！',
				'Description'=>'自从M.E这个代表“我们将与您同行，直到梦想实现”的名字频繁出现在朋友圈被转发，被分享，很多人都很好奇，到底M.E是怎样一个充满梦想的事业？为什么那么多传媒资深人士纷纷看好它的发展前景？为什么许多企业精英纷纷加入并全力以赴为之奋斗拼搏。',
				'PicUrl'=>'http://w.memyth.cn/mmbiz/ibtR2faqBicZcmNvr8ibme4b4AicIBJRgC4nNs0mMSfVib5x5gyESYOKdYbfWoDJjibeYLRehte4y23RRXhSb1LnLFwg/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=203552358&idx=1&sn=b28a020ea0db2323d45aefeba6aff520&scene=18#rd'
				)
			);
		 }
		 elseif($eventkey['key'] == 'EVENT_HISTORY') {
			 
			 
			$news1 =  array(
			     "6" => array(
				'Title'=>'新年奶粉大特惠|最好的奶粉免费空运给最乖的宝宝',
				'Description'=>'',
				'PicUrl'=> 'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZcvCkKzW4X2j4U74pq3kAsenDw1uMZX7tibSevjz141foMhtFa53ibubsO0iaWay6W3EElP8qoBeUSgA/0?wx_fmt=jpeg',
				'Url'=>'http://w.memyth.cn/posts/160203.php'
				),
				
			     "7" => array(
				'Title'=>'感悟｜2015大总结（太精辟了）',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZcicmXM9juCejqjcFYNYdXbLfvuEWvmwEADTPEHl8LcMIlyHoibuVth144ObTWCv31zuJWDVcy4qjyw/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400725419&idx=1&sn=1e3374f64f82f9545385b6df3c7cc541#rd'
				),
				
				"8" => array(
				'Title'=>'故事｜一个能把做娃娃这件事情玩到极致的妹纸...看完你会惊叹的...',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZcicmXM9juCejqjcFYNYdXbLVQj4SgTwQ6OkdCsw9XibwDqp44yQQVJgBXE26IiclgjLhMsZNBwiciaSBA/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400725381&idx=1&sn=303f00439913d44629660d4b00455763#rd'
				),
				 "9" => array(
				'Title'=>'父亲发布自己去世的假消息，家人们竟然···1分12秒泪目了',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZcicmXM9juCejqjcFYNYdXbLELciaAdE6QSz5By6fdAibUFBqN7vRoSXo3WXGjib15Z5S5ia8WU4DYia7zA/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400725343&idx=1&sn=07c7ae3b5e0d17d5f099d527cf32f6ca#rd'
				),
				 
				"10" => array(
				'Title'=>'【2016版】最值得在北美购买的礼品清单',
				'Description'=>'这两天，朋友圈被马克•扎克伯格决定捐出百分之内99股份的消息刷屏了，在大家称赞、怀疑的目光中，扎克伯格背后的女人普莉希拉•陈又一次进入了大家的视野。',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZdUoib3QPd79OWMQOISxaCVxhq2ic6KPBgsT68PiboPXYDHSMhDPibxFmLT3F9cUKanVYCdicRRB3oviaIg/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400601198&idx=1&sn=3e911f3394336a9bd782cbeb54bd18bb#rd'
				),
				
				"11" => array(
				'Title'=>'你只看到了扎克伯格的女儿，没有看到Max的妈妈',
				'Description'=>'这两天，朋友圈被马克•扎克伯格决定捐出百分之内99股份的消息刷屏了，在大家称赞、怀疑的目光中，扎克伯格背后的女人普莉希拉•陈又一次进入了大家的视野。',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZfRHcrkrYtw1429AQcDRHnJstAAIh9zgf6SELxSeib9fgTBiciasa4gS5et2RTuzoDia9mpMh7jcibU8rw/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400600770&idx=1&sn=eabd50b6279d4da2dfbb0f4d42d27558#rd'
				),
				
				"12" => array(
				'Title'=>'没错！超模辣妈米兰达亲授家常菜谱',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZdZSBE35e4h3CVkDUNcqrB02MXXVXNIl8fuquXRkxhick7JNqSN9QuSVNicBKEBXDtibDQpE8SSYjA4g/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400566956&idx=1&sn=c4aa9991dd28e67ed6b9b1b354414273#rd'
				),
				
				"13" => array(
				'Title'=>'中国最任性的女神：一字不识 身价70亿元',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZeGFicZXe8YC12yb2mqbO7NaWfNYib2skrF6VjDfR9yryAGoCwUByia8Bc8hicOofoCcQicdCwwuz4E61g/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400538825&idx=1&sn=a887f716d7dfa0fa8beb9a620982696a#wechat_redirect'
				),
				
				"14" => array(
				'Title'=>'【M.E绿生活】冬季进补有讲究 海参帮您抗寒冬',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZcyxX8VgW5TXrZ5oF9PbdkbTCSXUxetIOxMpZ2d733bxMzaM1a7Jfw9UUN0yxOOxpuibviaQjtLGTxg/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400468600&idx=1&sn=98ce9e7b75a76900192e11ec611e5817#rd'
				),
				
			 
				"15" => array(
				'Title'=>'输入[更早]两字查看更早的信息',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZfsj7bbQxfwMxxNmty7rEXlXpRo1eKc9EdtvJ9aP9fDFMwem0BQCpaibKC37WEb4xMRWlkcpHGHZkA/0?wx_fmt=jpeg',
				'Url'=>''
				)
				
			);
			$news2 = array(
			    "21" => array(
				'Title'=>'【M.E绿生活】加拿大食品安全再次夺得世界第一',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZeWSYqYU2Yl5YABuMXkFQOIOPAnugnK05K8f1ibkHwgfjCick9tPclntFB3UicbiaT90MDCIfNRGZCqHA/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400455917&idx=1&sn=3486b23c565312242f08b49435a5a838#rd'
				),
				
			    "16" => array(
				'Title'=>'【M.E绿生活】英国11岁少年超有头脑：开网店年入约63万元',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZfsj7bbQxfwMxxNmty7rEXlXpRo1eKc9EdtvJ9aP9fDFMwem0BQCpaibKC37WEb4xMRWlkcpHGHZkA/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400312983&idx=1&sn=bd17fc5030076ae41a1e0befca89bf91#rd'
				),
				"17" => array(
				'Title'=>'【M.E绿生活】加拿大值得代购的十大商品',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZdib2IpKdjeOIn9YTibYbAiaYav1kp4VPRFGx87Fyfic5f53mfeO6vPBjCFINvLoOkk3K7bKPHCl5rWug/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=400262927&idx=1&sn=04c631217fc04683bb2ec72de3d9bb9d#rd'
				),
				
				"18" => array(
				'Title'=>'【 M.E活动 】就是要免费环游世界 ——阳春三月，带上我爱的人一起飞',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZdEf5yZnunflDuBhblWHdd2VYdbkEdpF4zkubRZQwiccIQlte2ibXKpWSajZUMuiaPRfbjxU9SVO0ibyg/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=208626177&idx=1&sn=cc8bfb886aad95601079d5d2052dba75#wechat_redirect'
				),
				
				"19" => array(
				'Title'=>'【 M.E视频 】《温哥华女人》第二集，第三集新鲜出炉，为您讲述不一',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZeHgNmqRicFFWb02WqEh3HvNF4sFxnjotLxQY26LP3FIe3Xk91y7reZLSHPMphISKZQaZTnTjCgv5g/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=208582760&idx=1&sn=358b8d18429499ed02942f620981c631#rd'
				),
				 
				"20" => array(
				'Title'=>'【M.E绿生活】国庆献礼：加拿大知名品牌普润施白黎芦醇买10送1 祝福全球华人青春永驻 健康长寿10月01日',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZdcbbibBKGIDo374Lp341Yia6odL8Xw6wNlsXPFyicJU0eVibfokcriahxoiaMSNwVfjUICz1M2s2ia8O6Bw/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=208534787&idx=1&sn=c3773e40e80a013ef30cb216e2459d74#rd'
				)
				/*
				"21" => array(
				'Title'=>'【M.E分享】一个温哥华BCIT的毕业生，24岁一个人做了一个网站，36岁',
				'Description'=>'',
				'PicUrl'=>'https://mmbiz.qlogo.cn/mmbiz/ibtR2faqBicZe3DSLskyJ21iaOaXdyza8KEuoovDSZnA2jV2KAicFAxmnM3ia1V0d8lI5DqJh73x1psObbncEiavkxiaw/0?wx_fmt=jpeg',
				'Url'=>'http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=207711254&idx=1&sn=d5e8f276dd789bab26c24db2ce7a78d5#rd'
				)*/
			 	 
				
			);
			
			$weObj->transmitNews($news1);
			$weObj->transmitNews($news2);
			
		 }
		 else{//关注微信公众平台，产生一个订阅事件，即subscribe事件
			$weObj->text("您好,".$username."!\n\n欢迎您关注我们。 M.E全球是为千万中小企业主和在网络经济时代寻找新机会的创富者搭建的全新模式商业圈子。我们协助中小企业在网络时代成功营销推广，我们专卖货真价实的加拿大美国纯天然正牌正品，北美直邮到家，我们发布原创海外华人生活片，分享传播正能量，带给大家不一样的商业新模式。再次欢迎您加入M.E新媒体商业圈子！" )->reply();
		 }
		  //$weObj->text("help info ".$eventkey['key'])->reply();
		 $weObj->transmitNews($news);
		 
		 break;
	default:
			$weObj->text("help info")->reply();
			
/*const MSGTYPE_TEXT = 'text';
	const MSGTYPE_IMAGE = 'image';
	const MSGTYPE_LOCATION = 'location';
	const MSGTYPE_LINK = 'link';
	const MSGTYPE_EVENT = 'event';
	const MSGTYPE_MUSIC = 'music';
	const MSGTYPE_NEWS = 'news';
	const MSGTYPE_VOICE = 'voice';
	const MSGTYPE_VIDEO = 'video';
*/			
}
 
if(isset($_GET['action'])){ 
	$action = $_GET['action'];
	switch($action){
		case 'update-menu':
		    include "menu.php";
		    $postdata = $menu;
		 
		    $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";
			httpPost($url,$postdata);
		break;
		case 'sendall'://群发
			$url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$access_token";
			//$data = array("filter" => array("group_id" => 0), "mpnews" => array("media_id" => "eGQSlf2CqL3a4LePQTa0P0LHZ_hc8wCX57Xqk8-UV-c"), "msgtype" => "mpnews");
			$data = array("filter" => array("group_id" => 0),"mpnews" => array("media_id" => "C3XuHwUOGbtjmt5DsohUgiPE28AD8x5sfjXR4XYAMpQ"), "msgtype" => "mpnews");
			
			//$res = $weObj->sendMassMessage($data);
			$res = httpPost($url,json_encode($data));
			echo json_decode($res,ture);
		break;
		case 'get-user':
		    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=o4-5m0SCYmGaTnC56kClplh1ok8s&lang=zh_CN";
		    $output = httpGet($url);
			var_dump($output);
		break;
		case 'get-list'://获取素材列表获取某个素材的media_id
		    $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=$access_token";
			$data = array('type' => 'news', 'offset' => 0, 'count' => 1 );
			$res = httpPost($url,json_encode($data));
			echo json_decode($res,ture);
			break;
		case 'shareFriend':
			shareFriend();
			break;
		case 'shareTimeline':
			shareTimeline();
			break;
	} 
	
}
?>
  

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    debug: false,//debug: true,
    appId: '<?php echo $signPackage["appid"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["noncestr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
         'checkJsApi',
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'onMenuShareQZone',
        'hideMenuItems',
        'showMenuItems',
        'hideAllNonBaseMenuItem',
        'showAllNonBaseMenuItem',
        'translateVoice',
        'startRecord',
        'stopRecord',
        'onRecordEnd',
        'playVoice',
        'pauseVoice',
        'stopVoice',
        'uploadVoice',
        'downloadVoice',
        'chooseImage',
        'previewImage',
        'uploadImage',
        'downloadImage',
        'getNetworkType',
        'openLocation',
        'getLocation',
        'hideOptionMenu',
        'showOptionMenu',
        'closeWindow',
        'scanQRCode',
        'chooseWXPay',
        'openProductSpecificView',
        'addCard',
        'chooseCard',
        'openCard'
     ]
  });
 
</script>
<script> 
var imgUrl = 'http://w.memyth.cn/annual/PPT-Meeting-Bali-1.jpg'; 
var lineLink = 'http://w.memyth.cn/test.php'; 
var descContent = "阳春三月带上我爱的人一起飞"; 
var shareTitle = '【M.E活动】阳春三月带上我爱的人一起飞'; 
var appid = 'wxe18e720355871e06'; 
wx.ready(function(){  
    wx.onMenuShareTimeline({
        title: shareTitle, // 分享标题
        desc: descContent, // 分享描述
        link: lineLink,
        imgUrl: imgUrl, // 分享图标
    });
	wx.onMenuShareAppMessage({
        title: shareTitle, // 分享标题
        desc: descContent, // 分享描述
        link: lineLink,
        imgUrl: imgUrl, // 分享图标
        type: 'link', // 分享类型,music、video或link，不填默认为link
	});  
  
}); 
</script>
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/zepto.min.js"></script>
<script src="js/demo.js"> </script>
</html>

<?php
function get_web_page( $url )
 {
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }

 function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }
  
 function httpPost($url,$jsonData){
  		$ch = curl_init($url);
  		curl_setopt_array($ch, array(
   		 CURLOPT_POST => TRUE,
  		 CURLOPT_RETURNTRANSFER => TRUE,
   		 CURLOPT_HTTPHEADER => array(
       
        'Content-Type: application/json'
    	),
    	CURLOPT_POSTFIELDS => $jsonData));

		// Send the request
		$response = curl_exec($ch);

	// Check for errors
	if($response === FALSE){
    	die(curl_error($ch));
	}
	
	$responseData = json_decode($response, TRUE);
	
	var_dump($responseData);
  }
?>

</body>
</html>
