<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->pageTitle;?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="onenote.me" />
<meta name="copyright" content="Copyright (c) 2011 onenote.me All Rights Reserved." />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-basic.css');?>" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-main.css');?>" />
<script type="text/javascript" src="<?php echo sbu('scripts/jquery-1.6.4.min.js');?>"></script>
</head>
<body>
<div class="cd-wrapper cd-header">
	<a name="top"></a>
	<div id="logo" class="fl"><img src="http://img3.douban.com/pics/nav/lg_main_a7.png" /></div>
	<div class="site-nav fr">
    	<ul class="fl">
    		<li><a href="<?php echo aurl('post/latest');?>">最新</a></li>
    		<li><a href="<?php echo aurl('post/hour8');?>">最热</a></li>
    		<li><a href="<?php echo aurl('post/list', array('cid'=>11));?>">瞅瞅</a></li>
    		<li><a href="<?php echo aurl('tag/list');?>">标签</a></li>
    		<li><a href="<?php echo aurl('post/appraise');?>">鉴定</a></li>
    		<li><a href="<?php echo aurl('post/create');?>">加料</a></li>
    	</ul>
    	<ul class="fr">
    		<li><a href="<?php echo aurl('site/login');?>">登录</a></li>
    		<li><a href="<?php echo aurl('site/signup');?>">注册</a></li>
    	</ul>
    	<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
<div class="cd-wrapper cd-mainwrapper">
	<?php echo $content;?>
    <div class="clear"></div>
</div>
<div class="cd-wrapper cdc-block cdc-bottom-banner">
	<script type="text/javascript">
	<!--
        google_ad_client = "ca-pub-6304134167250488";
        /* meiapps_728x90 */
        google_ad_slot = "4771080120";
        google_ad_width = 728;
        google_ad_height = 90;
    //-->
    </script>
    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
<div class="cd-wrapper cd-footer">
	<p class="fl">内容版权所有 ©2005-2011 qiushibaike.com  苏ICP备11024271号-2</p><p class="fr">快乐就是要建立在别人的痛苦之上TOP</p>
</div>
</body>
</html>