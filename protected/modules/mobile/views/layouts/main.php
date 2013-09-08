<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $this->pageTitle;?></title>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-status-bar-style" content="default" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-itunes-app" content="app-id=486268988">
<link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo sbu('images/icon.png');?>" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo sbu('images/icon.png');?>" />
<link rel="apple-touch-startup-image" href="<?php echo sbu('images/startup.png');?>">
<script type="text/javascript">
var wdz_logined = <?php echo (int)!user()->isGuest;?>;
var wdz_quick_login_url = '<?php echo aurl('site/quicklogin');?>';
var _hmt = _hmt || [];
_hmt && _hmt.push(['_setCustomVar', 1, 'guest', <?php echo (int)user()->isGuest;?>, 2]);
</script>
<?php echo param('mobile_header_html');?>
</head>
<body>
<div class="cd-header">
    <div class="cd-wrapper">
    	<div id="site-logo" class="logo fleft">
    	    <a href="<?php echo CDBaseUrl::mobileHomeUrl();?>" title="点击返回首页">
    	        <img src="<?php echo sbu('images/logo.jpg');?>" alt="网站LOGO" title="返回首页" align="top" />
    	        <h2>挖段子网永久唯一域名：http://www.waduanzi.com</h2>
	        </a>
	    </div>
    	<ul class="channel-nav">
    		<li<?php echo ($this->channel=='latest') ? ' class="active"' : '';?>><a href="<?php echo aurl('mobile/channel/latest');?>">最新</a></li>
    		<li<?php echo ($this->channel=='hot') ? ' class="active"' : '';?>><a href="<?php echo aurl('mobile/channel/hot');?>">热门</a></li>
    		<li<?php echo ($this->channel==CHANNEL_FUNNY.MEDIA_TYPE_TEXT) ? ' class="active"' : '';?>><a href="<?php echo aurl('mobile/channel/joke');?>">笑话</a></li>
    		<li<?php echo ($this->channel==CHANNEL_FUNNY.MEDIA_TYPE_IMAGE) ? ' class="active"' : '';?>><a href="<?php echo aurl('mobile/channel/lengtu');?>">趣图</a></li>
    		<li<?php echo ($this->channel==CHANNEL_FOCUS) ? ' class="active"' : '';?>><a href="<?php echo aurl('mobile/channel/focus');?>">热点</a></li>
    	</ul>
    </div>
</div>

<div class="cd-entry cd-main">
    <div class="top-banner">
    <?php $this->widget('CDAdvert', array('solt'=>'mobile_top_banner'));?>
    <?php $this->widget('CDAdvert', array('solt'=>'mobile_top2_banner'));?>
    </div>
    
    <div class="app-online hide">
        <a href="<?php echo IPHONE_APP_URL;?>" target="_blank">挖段子iPhone应用3.1.0全新上线！！</a>
    </div>
    
    <div class="cd-wrapper"><?php echo $content;?></div>
</div>

<!-- 广告位 开始 -->
<div class="bottom-banner">
<?php $this->widget('CDAdvert', array('solt'=>'mobile_bottom_banner'));?>
</div>
<!-- 广告位 结束 -->

<footer class="clearfix">
    <a class="return-top" href="#top">返回顶部</a>
    <a class="switch-version" href="<?php echo aurl('site/index', array('f'=>1));?>">切换到桌面版</a>
</footer>
<?php echo param('mobile_footer_html');?>
<?php echo param('tongji_code');?>
</body>
</html>

<?php
cs()->registerCssFile(sbu('libs/bootstrap/css/bootstrap.min.css'))
    ->registerCssFile(sbu('styles/cd-mobile.css'))
    ->registerCoreScript('jquery')
    ->registerScriptFile(sbu('libs/bootstrap/js/bootstrap.min.js'), CClientScript::POS_END)
    ->registerScriptFile(sbu('scripts/cd-mobile.js'), CClientScript::POS_END);

CD_PRODUCT && cs()->scriptMap = array(
    'bootstrap.min.css' => sbu('styles/mobile-all.min.css?t=20130812001'),
    'cd-mobile.css' => sbu('styles/mobile-all.min.css?t=20130812001'),
    
    'jquery.min.js' => 'http://lib.sinaapp.com/js/jquery/1.9.0/jquery.min.js',
    
    'bootstrap.min.js' => sbu('scripts/mobile-all.min.js?t=20130812001'),
    'cd-mobile.js' => sbu('scripts/mobile-all.min.js?t=20130812001'),
    'json2.js' => sbu('scripts/mobile-all.min.js?t=20130812001'),
);
?>
