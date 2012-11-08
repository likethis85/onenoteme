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
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-member.css');?>" />
</head>
<body>
<div class="cd-mini-nav">
    <div class="cd-wrapper">
        <ul>
    		<li><a href="<?php echo CDBase::mobileHomeUrl();?>">手机版</a></li>
    		<li><a href="<?php echo CDBase::wapHomeUrl();?>">WAP版</a></li>
    		<li><a href="http://itunes.apple.com/cn/app/id486268988?mt=8" target="_blank">iPhone应用</a></li>
    		<li><a href="<?php echo sbu('android/waduanzi.apk');?>" target="_blank">安卓应用</a></li>
		</ul>
		<div class="clear"></div>
    </div>
</div>
<div class="cd-header">
    <div class="cd-wrapper">
    	<div id="site-logo" class="logo fleft">
    	    <a href="<?php echo CDBase::siteHomeUrl();?>" title="点击返回首页"><img src="<?php echo sbu('images/logo.jpg');?>" alt="网站LOGO" title="返回首页" align="top" />&nbsp;挖段子</a>
	    </div>
    	<ul class="channel-nav fleft">
    		<li<?php echo ($this->channel===CHANNEL_DUANZI) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/joke');?>">挖笑话</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_LENGTU) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/lengtu');?>">挖冷图</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_GIRL) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/girl');?>">挖福利</a></li>
    		<li>|</li>
    		<li<?php echo ($this->channel===CHANNEL_VIDEO) ? ' class="active"' : '';?>><a href="<?php echo aurl('channel/video');?>">挖视频</a></li>
    	</ul>
    	<div class="clear"></div>
    </div>
</div>
<div class="cd-wrapper cd-main">
    <?php $this->widget('zii.widgets.CBreadcrumbs', array('links'=>$this->breadcrumbs, 'skin'=>'member'));?>
    <div class="cd-sidebar fleft sidebar-nav">
        <div class="user-avatar">
            <a href="<?php echo aurl('member/profile/avatar');?>"><img src="<?php echo $this->profile->avatar_large;?>" /></a>
            <h4><a href="<?php echo aurl('member/profile/index');?>"><?php echo $this->username;?></a></h4>
        </div>
        <ul class="member-nav">
            <li><a href="<?php echo aurl('profile/index');?>" <?php if ($this->channel == 'home') echo 'class="active"';?>>我的基本资料</a></li>
            <li><a href="#">我发布的段子</a></li>
            <li><a href="#">我的收藏</a></li>
            <li><a href="#">我的评论</a></li>
            <li><div class="space10px"></div></li>
            <li><a href="<?php echo aurl('profile/avatar');?>" <?php if ($this->channel == 'avatar') echo 'class="active"';?>>修改头像</a></li>
            <li><a href="<?php echo aurl('profile/email');?>" <?php if ($this->channel == 'email') echo 'class="active"';?>>修改邮箱</a></li>
            <li><a href="<?php echo aurl('profile/passwd');?>" <?php if ($this->channel == 'passwd') echo 'class="active"';?>>修改密码</a></li>
            <li><a href="<?php echo CDBase::logoutUrl();?>">退出登录</a></li>
        </ul>
    </div>
    <div class="cd-container fright">
        <h2 class="channel-title"><?php echo $this->title;?></h2>
        <?php echo $content;?>
    </div>
    <div class="clear"></div>
</div>
<?php echo param('footer_before_html');?>
<div class="cd-footer">
    <div class="cd-wrapper">
    	<p class="fleft">内容版权所有 ©2011-2012 <a href="<?php echo CDBase::siteHomeUrl();?>">waduanzi.com</a>&nbsp;&nbsp;冀ICP备12006196号-5&nbsp;&nbsp;
    	    <a href="<?php echo aurl('site/bdmap');?>" target="_blank">网站地图</a>&nbsp;&nbsp;
    	    <a href="<?php echo aurl('site/links');?>" target="_blank">友情链接</a>&nbsp;&nbsp;
    	    <a href="<?php echo aurl('tag/list');?>" target="_blank">全部标签</a>
        </p>
    	<p class="fright">笑死人不尝命&nbsp;<a href="#top">TOP</a></p>
    	<div class="clear"></div>
	</div>
</div>

<?php echo param('tongji_code');?>
</body>
</html>

<?php
cs()->registerCoreScript('jquery');
cs()->registerScriptFile(sbu('scripts/cd-member.js'), CClientScript::POS_END);
?>



