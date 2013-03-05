<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $this->pageTitle;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-status-bar-style" content="default" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<script type="text/javascript">
var wdz_logined = <?php echo (int)!user()->isGuest;?>;
var wdz_quick_login_url = '<?php echo aurl('site/quicklogin');?>';
var _hmt = _hmt || [];
_hmt && _hmt.push(['_setCustomVar', 1, 'guest', <?php echo (int)user()->isGuest;?>, 2]);
</script>
</head>
<body>
<div class="cd-header">
    <div class="cd-wrapper">
    	<div id="site-logo" class="logo fleft">
    	    <a href="<?php echo CDBase::mobileHomeUrl();?>" title="点击返回首页">
    	        <img src="<?php echo sbu('images/logo.jpg');?>" alt="网站LOGO" title="返回首页" align="top" /><h1>挖段子</h1>
    	        <h2>挖段子网永久唯一域名：http://www.waduanzi.com</h2>
	        </a>
	    </div>
    	<ul class="channel-nav">
    		<li<?php echo ($this->channel===CHANNEL_DUANZI) ? ' class="active"' : '';?>><a href="<?php echo aurl('mobile/channel/joke');?>">挖笑话</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_LENGTU) ? ' class="active"' : '';?>><a href="<?php echo aurl('mobile/channel/lengtu');?>">挖趣图</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_GIRL) ? ' class="active"' : '';?>><a href="<?php echo aurl('mobile/channel/girl');?>">挖女神</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_VIDEO) ? ' class="active"' : '';?>><a href="<?php echo aurl('mobile/channel/video');?>">挖视频</a></li>
    	</ul>
    </div>
</div>

<div class="cd-wrapper cd-main">
	<?php echo $content;?>
</div>

<!-- 广告位 开始 -->
<div class="cdc-block">
    <?php $this->widget('CDAdvert', array('solt'=>'mobile_banner'));?>
</div>
<!-- 广告位 结束 -->

<footer class="clearfix">
    <a class="return-top" href="#top">返回顶部</a>
    <a class="switch-version" href="<?php echo aurl('site/index', array('f'=>1));?>">切换到桌面版</a>
</footer>
<?php echo param('tongji_code');?>
<script src="http://a.tbcdn.cn/apps/top/x/sdk.js?appkey=21351161"></script>
</body>
</html>

<?php
cs()->registerCssFile(sbu('libs/bootstrap/css/bootstrap.min.css'))
    ->registerCssFile(sbu('styles/cd-mobile.css'))
    ->registerCoreScript('jquery')
    ->registerScriptFile(sbu('libs/bootstrap/js/bootstrap.min.js'), CClientScript::POS_END)
    ->registerScriptFile(sbu('scripts/cd-mobile.js'), CClientScript::POS_END);

CD_PRODUCT && cs()->scriptMap = array(
    'bootstrap.min.css' => sbu('styles/mobile-all.min.css?t=20130302002'),
    'cd-mobile.css' => sbu('styles/mobile-all.min.css?t=20130302002'),
    'bootstrap.min.js' => sbu('scripts/mobile-all.min.js?t=20130305002'),
    'cd-mobile.js' => sbu('scripts/mobile-all.min.js?t=20130305002'),
    'json2.js' => sbu('scripts/mobile-all.min.js?t=20130305002'),
);
?>
