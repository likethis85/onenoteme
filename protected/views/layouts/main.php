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
	<div id="logo" class="fl"><a href="<?php echo app()->homeUrl;?>" title="点击返回首页"><img src="<?php echo sbu('images/logo.png');?>" alt="挖段子LOGO" /></a></div>
	<div class="site-nav fr">
		<div class="user-nav ar">
    		<?php $this->renderDynamic('userToolbar');?>
		</div>
    	<ul class="channel-nav">
    		<li <?php echo ($this->channel == 'latest') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('post/latest');?>">最新</a></li>
    		<li>/</li>
    		<li <?php echo ($this->channel == 'hottop') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('post/day');?>">最热</a></li>
    		<li>/</li>
    		<li <?php echo ($this->channel == 'chouchou') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('post/list', array('cid'=>11));?>">瞅瞅</a></li>
    		<li>/</li>
    		<li <?php echo ($this->channel == 'tag') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('tag/list');?>">标签</a></li>
    		<li>/</li>
    		<li <?php echo ($this->channel == 'appraise') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('post/appraise');?>">鉴定</a></li>
    		<li>/</li>
    		<li <?php echo ($this->channel == 'create') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('post/create');?>">加料</a></li>
    		<div class="clear"></div>
    	</ul>
	</div>
	<div class="clear"></div>
	<div class="bgline"></div>
</div>
<div class="cd-wrapper cd-mainwrapper">
	<?php echo $content;?>
</div>

<div class="cd-wrapper cd-footer">
	<p class="fl">内容版权所有 ©2011 <a href="">onenote.me</a>  鲁ICP备11024271号-2</p><p class="fr"><a href="#top">TOP</a></p>
	<div class="clear"></div>
</div>

<?php $this->renderPartial('/public/tongji');?>
</body>
</html>

<?php cs()->registerScriptFile(sbu('scripts/cd-onenote.js'), CClientScript::POS_END);?>


