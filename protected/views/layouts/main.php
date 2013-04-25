<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->pageTitle;?></title>
<meta name="author" content="waduanzi.com" />
<meta name="copyright" content="Copyright (c) 2011-2013 waduanzi.com All Rights Reserved." />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta name="application-name" content="挖段子网"/>
<meta name="msapplication-TileColor" content="#0c95f0"/>
<meta name="msapplication-TileImage" content="35c7b7b9-f757-4e10-83a9-d18cc277e4da.png"/>
<script type="text/javascript">
var wdz_logined = <?php echo (int)!user()->isGuest;?>;
var wdz_quick_login_url = '<?php echo aurl('site/quicklogin');?>';
var _hmt = _hmt || [];
_hmt && _hmt.push(['_setCustomVar', 1, 'guest', <?php echo (int)user()->isGuest;?>, 2]);
</script>
<?php echo param('header_html');?>
</head>
<body>
<div class="cd-mini-nav">
    <ul class="fleft">
		<li><a href="<?php echo CDBaseUrl::mobileHomeUrl();?>">手机版</a></li>
		<li><a href="<?php echo CDBaseUrl::wapHomeUrl();?>">WAP版</a></li>
		<li><a href="http://itunes.apple.com/cn/app/id486268988?mt=8" target="_blank">iPhone应用</a></li>
		<li><a href="<?php echo sbu('android/waduanzi.apk');?>" target="_blank">安卓应用</a></li>
	</ul>
	<ul class="fright" id="user-mini-nav">
	    <?php if (user()->getIsAdmin()):?>
	    <li><a href="<?php echo CDBaseUrl::adminHomeUrl()?>" target="_blank">管理中心</a></li>
	    <?php endif;?>
	    <li><a href="<?php echo aurl('sponsor/index');?>" target="_blank">赞助我们</a></li>
	    <?php echo $this->renderDynamic('userToolbar');?>
	</ul>
	<div class="clear"></div>
</div>
<div class="cd-header">
    <div class="cd-wrapper cd-header-inner">
    	<div id="site-logo" class="logo fleft">
    	    <a href="<?php echo CDBaseUrl::siteHomeUrl();?>" title="点击返回首页">
    	        <img src="<?php echo sbu('images/logo.jpg');?>" alt="网站LOGO" title="返回首页" align="top" width="45" height="45" /><h1>挖段子</h1>
    	        <h2>挖段子网永久唯一域名：http://www.waduanzi.com</h2>
	        </a>
	    </div>
    	<ul class="channel-nav fleft">
    		<li<?php echo ($this->channel===CHANNEL_DUANZI) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/joke');?>">挖笑话</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_LENGTU) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/lengtu');?>">挖趣图</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_GIRL) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/girl');?>">挖女神</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_VIDEO) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/video');?>">挖视频</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_GHOSTSTORY) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/ghost');?>">挖鬼故事</a></li>
    	</ul>
    	<ul class="fright">
    	</ul>
    	<a href="javascript:void(0);" id="wxqrcode"><img src="<?php echo sbu('images/qrcode_wx.jpg');?>" alt="挖段子公众账号二维码" /></a>
    	<div class="clear"></div>
    </div>
</div>
<div class="cd-wrapper cd-main">
    <div class="alert alert-block alert-success alert-sitetip wx-help hide">
        微信公众账号添加新功能：<br />
        回复 1 返回笑话内容，现在是时候回复 13，14，1x ...了，猜的没错，现在可以返回多条了，14表示，返回 4 条笑话内容<br />
        此功能当前只对笑话有效，趣图和女神还是只返回一条，但请记住：我们一直在努力！
    </div>
	<?php echo $content;?>
</div>
<?php echo param('footer_before_html');?>
<div class="cd-footer">
    <div class="cd-wrapper">
    	<p class="fleft">&copy;2011-2013 <a href="<?php echo CDBaseUrl::siteHomeUrl();?>">waduanzi.com</a>&nbsp;&nbsp;苏ICP备12075579号-3&nbsp;&nbsp;
    	    <a href="<?php echo aurl('site/bdmap');?>" target="_blank">网站地图</a>&nbsp;&nbsp;
    	    <a href="<?php echo aurl('site/links');?>" target="_blank">友情链接</a>&nbsp;&nbsp;
    	    <a href="<?php echo aurl('tag/list');?>" target="_blank">全部标签</a>
    	    <a href="<?php echo CDBaseUrl::siteHomeUrl();?>" target="_blank">总共：<?php echo Post::allCount();?>篇</a>
        </p>
    	<p class="fright">笑死人不尝命&nbsp;<a href="#top">TOP</a></p>
    	<div class="clear"></div>
	</div>
</div>

<div id="quick-login-modal" class="modal fade hide" role="dialog" aria-hidden="true">
    <div class="modal-body"></div>
</div>
<div id="big-wxqrcode"><img src="<?php echo sbu('images/qrcode_wx.jpg');?>" alt="挖段子公众账号二维码" /></div>
<?php
echo param('footer_after_html');
echo param('tongji_code');
?>
<script src="http://l.tbcdn.cn/apps/top/x/sdk.js?appkey=21351161"></script>
</body>
</html>

<?php
cs()->registerMetaTag('text/html; charset=utf-8', null, 'content-type')
    ->registerCssFile(sbu('libs/bootstrap/css/bootstrap.min.css'))
    ->registerCssFile(sbu('styles/cd-basic.css'))
    ->registerCssFile(sbu('styles/cd-main.css'))
    ->registerCoreScript('jquery')
    ->registerScriptFile(sbu('libs/modernizr.min.js'), CClientScript::POS_END)
    ->registerScriptFile(sbu('libs/bootstrap/js/bootstrap.min.js'), CClientScript::POS_END)
    ->registerScriptFile(sbu('scripts/cd-main.js'), CClientScript::POS_END)
    ->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/index'), null, array('title'=>app()->name . ' » Feed'));

CD_PRODUCT && cs()->scriptMap = array(
    'bootstrap.min.css' => sbu('styles/cd-all.min.css?t=20130412002'),
    'cd-basic.css' => sbu('styles/cd-all.min.css?t=20130412002'),
    'cd-main.css' => sbu('styles/cd-all.min.css?t=20130412002'),
    
    'jquery.min.js' => 'http://lib.sinaapp.com/js/jquery/1.9.0/jquery.min.js',
    
    'modernizr.min.js' => sbu('scripts/cd-all.min.js?t=20130412001'),
    'bootstrap.min.js' => sbu('scripts/cd-all.min.js?t=20130412001'),
    'jquery.lazyload.min.js' => sbu('scripts/cd-all.min.js?t=20130412001'),
    'jquery.infinitescroll.min.js' => sbu('scripts/cd-all.min.js?t=20130412001'),
    'jquery.masonry.min.js' => sbu('scripts/cd-all.min.js?t=20130412001'),
    'cd-main.js' => sbu('scripts/cd-all.min.js?t=20130412001'),
    'json2.js' => sbu('scripts/cd-all.min.js?t=20130412001'),
);
?>

