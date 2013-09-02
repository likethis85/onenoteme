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
var wdz_quick_login_url = '<?php echo aurl('account/quicklogin');?>';
var _hmt = _hmt || [];
_hmt && _hmt.push(['_setCustomVar', 1, 'guest', <?php echo (int)user()->isGuest;?>, 2]);
</script>
<?php echo param('header_html');?>
</head>
<body>
<div class="cd-mini-nav">
    <ul class="fleft">
		<li><a href="<?php echo CDBaseUrl::mobileHomeUrl();?>">手机版</a></li>
		<li><a href="http://itunes.apple.com/cn/app/id486268988?mt=8" target="_blank">iPhone应用</a></li>
		<li><a href="<?php echo sbu('android/waduanzi.apk');?>" target="_blank">安卓应用</a></li>
		<li><a href="<?php echo aurl('sponsor/index');?>" target="_blank">赞助我们</a></li>
	</ul>
	<ul class="fright" id="user-mini-nav">
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
    		<li class="top-menu"><a <?php if ($this->channel=='latest') echo ' class="active"';?> href="<?php echo aurl('channel/latest');?>">刚出炉</a></li>
    	    <li class="top-menu">
    		    <a href="<?php echo url('channel/hot');?>" class="site-bg dropmenu<?php if ($this->channel=='hot') echo ' active';?>">最热门</a>
    		    <ul class="submenu">
        		    <li><a href="<?php echo url('channel/day');?>">24小时内</a></li>
        		    <li><a href="<?php echo url('channel/week');?>">一周内</a></li>
        		    <li><a href="<?php echo url('channel/month');?>">一月内</a></li>
    		    </ul>
		    </li>
    		<li class="top-menu"><a <?php if ($this->channel==CHANNEL_FUNNY.MEDIA_TYPE_TEXT) echo ' class="active"';?> href="<?php echo url('channel/joke');?>">挖笑话</a></li>
    		<li class="top-menu"><a <?php if ($this->channel==CHANNEL_FUNNY.MEDIA_TYPE_IMAGE) echo ' class="active"';?> href="<?php echo url('channel/lengtu');?>">挖趣图</a></li>
    		<li class="top-menu"><a <?php if ($this->channel==CHANNEL_FOCUS) echo ' class="active"';?> href="<?php echo url('channel/focus');?>">挖热点</a></li>
    		<li class="top-menu"><a <?php if ($this->channel=='publish') echo ' class="active"';?> href="<?php echo CDBaseUrl::publishUrl()?>">投稿</a></li>
    	</ul>
    	<ul class="channel-nav fright">
    	</ul>
    	<div class="clear"></div>
    </div>
</div>
<div class="cd-wrapper cd-main">
	<?php echo $content;?>
</div>
<?php echo param('footer_before_html');?>
<?php $this->renderPartial('/public/footer');?>

<div id="quick-login-modal" class="modal fade hide" role="dialog" aria-hidden="true">
    <div class="modal-body"></div>
</div>

<?php echo param('footer_after_html');?>
<?php $this->widget('CDAdvert', array('solt'=>'page_right_corner', 'onlyCode'=>true));?>
<?php echo param('tongji_code');?>
</body>
</html>

<?php
cs()->registerMetaTag('text/html; charset=utf-8', null, 'content-type')
    ->registerCssFile(sbu('libs/bootstrap/css/bootstrap.min.css'))
    ->registerCssFile(sbu('styles/cd-basic.css'))
    ->registerCssFile(sbu('styles/cd-main.css'))
    ->registerCoreScript('jquery')
    ->registerScriptFile(sbu('libs/json2.js'), CClientScript::POS_END)
    ->registerScriptFile(sbu('libs/modernizr.min.js'), CClientScript::POS_END)
    ->registerScriptFile(sbu('libs/bootstrap/js/bootstrap.min.js'), CClientScript::POS_END)
    ->registerScriptFile(sbu('scripts/cd-main.js'), CClientScript::POS_END)
    ->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/index'), null, array('title'=>app()->name . ' » Feed'));

CD_PRODUCT && cs()->scriptMap = array(
    'bootstrap.min.css' => sbu('styles/cd-all.min.css?t=20130629001'),
    'cd-basic.css' => sbu('styles/cd-all.min.css?t=20130629001'),
    'cd-main.css' => sbu('styles/cd-all.min.css?t=20130629001'),
    
    'jquery.min.js' => 'http://lib.sinaapp.com/js/jquery/1.9.0/jquery.min.js',

    'json2.js' => sbu('scripts/cd-all.min.js?t=20130521002'),
    'modernizr.min.js' => sbu('scripts/cd-all.min.js?t=20130521002'),
    'bootstrap.min.js' => sbu('scripts/cd-all.min.js?t=20130521002'),
    'jquery.lazyload.min.js' => sbu('scripts/cd-all.min.js?t=20130521002'),
    'cd-main.js' => sbu('scripts/cd-all.min.js?t=20130521002'),
);
?>

