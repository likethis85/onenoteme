<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->pageTitle;?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="waduanzi.com" />
<meta name="copyright" content="Copyright (c) 2011-2912 waduanzi.com All Rights Reserved." />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="shortcut icon" href="<?php echo sbu('images/favicon.ico');?>" type="image/vnd.microsoft.icon" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('libs/bootstrap/css/bootstrap.min.css');?>" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-basic.css?t=20130201001');?>" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-member.css');?>" />
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
	    <?php echo $this->renderDynamic('userToolbar');?>
	</ul>
	<div class="clear"></div>
</div>
<div class="cd-header">
    <div class="cd-wrapper cd-header-inner"">
    	<div id="site-logo" class="logo fleft">
    	    <a href="<?php echo CDBaseUrl::siteHomeUrl();?>" title="点击返回首页">
    	        <img src="<?php echo sbu('images/logo.jpg');?>" alt="网站LOGO" title="返回首页" align="top" width="45" height="45" /><h1>挖段子</h1>
    	        <h2>挖段子网永久唯一域名：http://www.waduanzi.com</h2>
	        </a>
	    </div>
    	<ul class="channel-nav fleft">
    		<li class="top-menu"><a <?php if ($this->channel=='latest') echo ' class="active"';?> href="<?php echo aurl('channel/latest');?>">刚出炉</a></li>
    	    <li class="top-menu">
    		    <a href="<?php echo url('channel/hot');?>">最热门</a>
    		    <ul class="submenu">
        		    <li><a href="<?php echo url('channel/day');?>">24小时内</a></li>
        		    <li><a href="<?php echo url('channel/week');?>">一周内</a></li>
        		    <li><a href="<?php echo url('channel/month');?>">一月内</a></li>
    		    </ul>
		    </li>
    		<li class="top-menu"><a href="<?php echo url('channel/joke');?>">挖笑话</a></li>
    		<li class="top-menu"><a href="<?php echo url('channel/lengtu');?>">挖趣图</a></li>
    	</ul>
    	<ul class="fright">
    	</ul>
    	<a href="javascript:void(0);" id="wxqrcode"><img src="<?php echo sbu('images/qrcode_wx.jpg');?>" alt="挖段子公众账号二维码" /></a>
    	<div class="clear"></div>
    </div>
</div>
<div class="cd-wrapper cd-main">
    
    <div class="cd-sidebar fleft sidebar-nav">
        <div class="user-score">积分：<b><?php echo (int)$this->profile->score;?></b></div>
        <div class="user-avatar">
            <a href="<?php echo aurl('member/profile/avatar');?>"><?php echo $this->profile->largeAvatar;?></a>
            <h5><?php echo $this->nickname;?></h5>
        </div>
        <ul class="member-nav">
            <li><a href="<?php echo aurl('member/post/favorite');?>" <?php if ($this->menu == 'favorite') echo 'class="active"';?>>我的收藏</a></li>
            <li><a href="<?php echo aurl('member/post/index');?>" <?php if ($this->menu == 'post') echo 'class="active"';?>>我的段子</a></li>
            <li><a href="<?php echo aurl('member/comment/index');?>" <?php if ($this->menu == 'comment') echo 'class="active"';?>>我的评论</a></li>
            <li><div class="space10px"></div></li>
            <li><a href="<?php echo aurl('member/profile/index');?>" <?php if ($this->menu == 'profile') echo 'class="active"';?>>基本资料</a></li>
            <li><a href="<?php echo aurl('member/profile/avatar');?>" <?php if ($this->menu == 'avatar') echo 'class="active"';?>>修改头像</a></li>
            <li><a href="<?php echo aurl('member/profile/nickname');?>" <?php if ($this->menu == 'nickname') echo 'class="active"';?>>修改昵称</a></li>
            <li><a href="<?php echo aurl('member/profile/passwd');?>" <?php if ($this->menu == 'passwd') echo 'class="active"';?>>修改密码</a></li>
            <li><a href="<?php echo CDBaseUrl::logoutUrl(CDBaseUrl::siteHomeUrl());?>">退出登录</a></li>
        </ul>
    </div>
    <div class="cd-container fright">
        <?php $this->widget('zii.widgets.CBreadcrumbs', array('links'=>$this->breadcrumbs, 'skin'=>'member'));?>
        <?php echo $content;?>
    </div>
    <div class="clear"></div>
</div>
<?php echo param('footer_before_html');?>
<?php $this->renderPartial('//public/footer');?>

<?php echo param('tongji_code');?>
</body>
</html>

<?php
cs()->registerCoreScript('jquery');
cs()->registerScriptFile(sbu('scripts/cd-member.js'), CClientScript::POS_END);
cs()->registerScriptFile(sbu('libs/bootstrap/js/bootstrap.min.js'), CClientScript::POS_END);
?>



