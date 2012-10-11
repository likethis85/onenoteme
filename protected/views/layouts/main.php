<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->pageTitle;?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="waduanzi.com" />
<meta name="copyright" content="Copyright (c) 2011-2912 waduanzi.com All Rights Reserved." />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="shortcut icon" href="<?php echo sbu('images/favicon.ico');?>" type="image/vnd.microsoft.icon" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-basic.css');?>" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-all.css');?>" />
</head>
<body>
<div class="cd-header">
    <div class="cd-wrapper">
    	<div id="site-logo" class="logo fleft">
    	    <a href="<?php echo app()->homeUrl;?>" title="点击返回首页"><img src="<?php echo sbu('images/logo.jpg');?>" alt="网站LOGO" title="返回首页" align="top" />&nbsp;挖段子</a>
	    </div>
    	<ul class="channel-nav fleft">
    		<li<?php echo ($this->channel===CHANNEL_LENGTU) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/lengtu');?>">挖冷图</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_GIRL) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/girl');?>">挖福利</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_DUANZI) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/duanzi');?>">挖段子</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_VIDEO) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/video');?>">挖短片</a></li>
    	</ul>
		<ul class="user-nav fright">
    		<li><a href="http://m.waduanzi.com">手机版</a></li>
    		<li><a href="http://itunes.apple.com/cn/app/id486268988?mt=8" target="_blank">iPhone应用</a></li>
    		<li><a href="http://s.waduanzi.com/android/waduanzi.apk" target="_blank">安卓应用</a></li>
    		<li class="diviler"></li>
    		<?php echo $this->renderDynamic('userToolbar');?>
		</ul>
    	<div class="clear"></div>
    </div>
</div>
<div class="cd-wrapper">
	<?php echo $content;?>
</div>

<div class="cd-footer">
    <div class="cd-wrapper">
    	<p class="fleft">内容版权所有 ©2011-2012 <a href="">waduanzi.com</a>&nbsp;&nbsp;冀ICP备12006196号-5&nbsp;&nbsp;
    	    <a href="<?php echo aurl('site/baidumap');?>" target="_blank">网站地图</a>&nbsp;&nbsp;
    	    <a href="<?php echo aurl('site/links');?>" target="_blank">友情链接</a>
        </p>
    	<p class="fright">笑死人不尝命&nbsp;<a href="#top">TOP</a></p>
    	<div class="clear"></div>
	</div>
</div>
<?php if (user()->getIsGuest()):?>
<div id="quick-login">
    <h1>还未实现</h1>
</div>
<?php endif;?>

<?php $this->renderPartial('/public/tongji');?>
</body>
</html>

<?php if (user()->getIsGuest()) cs()->registerCoreScript('jquery.ui');?>
<?php cs()->registerScriptFile(sbu('scripts/cd-main.js'), CClientScript::POS_END);?>

