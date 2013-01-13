<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->pageTitle;?></title>
<meta name="author" content="waduanzi.com" />
<meta name="copyright" content="Copyright (c) 2011-2912 waduanzi.com All Rights Reserved." />
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
		<li><a href="<?php echo CDBase::mobileHomeUrl();?>">手机版</a></li>
		<li><a href="<?php echo CDBase::wapHomeUrl();?>">WAP版</a></li>
		<li><a href="http://itunes.apple.com/cn/app/id486268988?mt=8" target="_blank">iPhone应用</a></li>
		<li><a href="<?php echo sbu('android/waduanzi.apk');?>" target="_blank">安卓应用</a></li>
	</ul>
	<ul class="fright" id="user-mini-nav">
	    <?php echo $this->renderDynamic('userToolbar');?>
	</ul>
	<div class="clear"></div>
</div>
<div class="cd-header">
    <div class="cd-wrapper">
    	<div id="site-logo" class="logo fleft">
    	    <a href="<?php echo CDBase::siteHomeUrl();?>" title="点击返回首页"><img src="<?php echo sbu('images/logo.jpg');?>" alt="网站LOGO" title="返回首页" align="top" />&nbsp;挖段子</a>
	    </div>
    	<ul class="channel-nav fleft">
    		<li<?php echo ($this->channel===CHANNEL_DUANZI) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/joke');?>">挖笑话</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_LENGTU) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/lengtu');?>">挖趣图</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_GIRL) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/girl');?>">挖女神</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_VIDEO) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/video');?>">挖视频</a></li>
    	</ul>
    	<ul class="fright">
    	    <li><img id="small-wxqrcode" src="<?php echo sbu('images/qrcode_wx.jpg');?>" alt="挖段子公众账号二维码" /></li>
    	</ul>
    	<div class="clear"></div>
    </div>
</div>
<div class="cd-wrapper cd-main">
    <div class="alert alert-block alert-success alert-sitetip wx-help hide">
        挖段子微信公众账号全面升级，笑话、趣图、女神都可以查看啦！<br />
        使用微信扫描首页侧边栏上的<a href="<?php echo sbu('images/qrcode_wx.jpg');?>" class="cred" target="_blank">二维码</a>或直接使用微信添加“<strong class="cred">挖段子</strong>”或“<strong class="cred">waduanzi</strong>”为好友即可使用，方便快捷。<br />
        回复 1 查看笑话；回复 2 查看趣图；回复 3 查看女神；回复 0 查看使用帮助。
    </div>
	<?php echo $content;?>
</div>
<?php echo param('footer_before_html');?>
<div class="cd-footer">
    <div class="cd-wrapper">
    	<p class="fleft">内容版权所有 ©2011-2012 <a href="<?php echo CDBase::siteHomeUrl();?>">waduanzi.com</a>&nbsp;&nbsp;苏ICP备12075579号-3&nbsp;&nbsp;
    	    <a href="<?php echo aurl('site/bdmap');?>" target="_blank">网站地图</a>&nbsp;&nbsp;
    	    <a href="<?php echo aurl('site/links');?>" target="_blank">友情链接</a>&nbsp;&nbsp;
    	    <a href="<?php echo aurl('tag/list');?>" target="_blank">全部标签</a>
    	    <a href="<?php echo CDBase::siteHomeUrl();?>" target="_blank">总共：<?php echo Post::allCount();?>篇</a>
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
    ->registerScriptFile(sbu('libs/bootstrap/js/bootstrap.min.js'), CClientScript::POS_END)
    ->registerScriptFile(sbu('scripts/cd-main.js'), CClientScript::POS_END)
    ->registerLinkTag('alternate', 'application/rss+xml', aurl('feed'), null, array('title'=>app()->name . ' » Feed'));

YII_DEBUG || cs()->scriptMap = array(
    'bootstrap.min.css' => sbu('styles/cd-all.min.css?t=2013014001'),
    'cd-basic.css' => sbu('styles/cd-all.min.css?t=2013014001'),
    'cd-main.css' => sbu('styles/cd-all.min.css?t=2013011001'),
    'bootstrap.min.js' => sbu('scripts/cd-all.min.js?t=2013011001'),
    'jquery.lazyload.min.js' => sbu('scripts/cd-all.min.js?t=2013011001'),
    'jquery.infinitescroll.min.js' => sbu('scripts/cd-all.min.js?t=2013011001'),
    'jquery.masonry.min.js' => sbu('scripts/cd-all.min.js?t=2013011001'),
    'cd-main.js' => sbu('scripts/cd-all.min.js?t=2013011001'),
    'json.js' => sbu('scripts/cd-all.min.js?t=2013011001'),
);
?>

