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
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-main.css?v=2011112401');?>" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/newmain.css');?>" />
<script type="text/javascript" src="<?php echo sbu('libs/jquery-1.7.1.min.js');?>"></script>
</head>
<body>
<!-- <div class="cd-wrapper"><img src="http://images.infzm.com/medias/2012/0120/50620.jpeg" alt="新春如意，龙年吉祥" /></div> -->
<div class="cd-wrapper cd-header">
	<a name="top"></a>
	<div id="logo" class="fl"><a href="<?php echo app()->homeUrl;?>" title="点击返回首页"><img src="<?php echo sbu('images/logo.png');?>" alt="挖段子LOGO" /></a></div>
	<!-- 将此标记放在您希望显示like按钮的位置 -->
    <div class="bdlikebutton fl" style="margin-top:10px;"></div>
    <div class="site-nav fr">
		<div class="user-nav ar">
    		<?php $this->renderDynamic('userToolbar');?>
    		&nbsp;&nbsp;|&nbsp;<a href="http://m.waduanzi.com">手机版</a>
		</div>
    	<ul class="channel-nav">
    		<li <?php echo ($this->channel == 'live') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('post/live');?>">直播</a></li>
    		<li>/</li>
    		<li <?php echo ($this->channel == 'hottest') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('post/hottest');?>">排行榜</a></li>
    		<li>/</li>
    		<li <?php echo ($this->channel == 'girl') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('channel/girl');?>">挖福利</a></li>
    		<li>/</li>
    		<li <?php echo ($this->channel == 'lengtu') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('channel/lengtu');?>">挖冷图</a></li>
    		<li>/</li>
    		<li <?php echo ($this->channel == 'duanzi') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('channel/duanzi');?>">挖段子</a></li>
    		<li>/</li>
    		<!-- <li <?php echo ($this->channel == 'video') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('channel/video');?>">挖好片</a></li>
    		<li>/</li> -->
    		<li <?php echo ($this->channel == 'tag') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('tag/list');?>">标签</a></li>
    		<li>/</li>
    		<!-- <li <?php echo ($this->channel == 'appraise') ? 'class="channel-hover"' : '';?>><a href="<?php echo aurl('post/appraise');?>">鉴定</a></li>
    		<li>/</li> -->
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
	<p class="fl">内容版权所有 ©2011-2012 <a href="">waduanzi.com</a>&nbsp;&nbsp;冀ICP备12006196号-5</p><p class="fr">笑死人不尝命&nbsp;<a href="#top">TOP</a></p>
	<div class="clear"></div>
</div>
<div class="space10px"></div>

<!-- 将此代码放在适当的位置，建议在body结束前 -->
<script id="bdlike_shell"></script>
<script>
    var bdShare_config = {
    	"type":"small",
    	"color":"orange",
    	"uid":"541407"
    };
    document.getElementById("bdlike_shell").src="http://bdimg.share.baidu.com/static/js/like_shell.js?t=" + new Date().getHours();
</script>
	
	
<!-- Baidu Button BEGIN -->
<script type="text/javascript" id="bdshare_js" data="type=slide&amp;img=4&amp;pos=left&amp;uid=541407" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
	document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
</script>
<!-- Baidu Button END -->

<?php $this->renderPartial('/public/tongji');?>
</body>
</html>

<?php cs()->registerScriptFile(sbu('scripts/cd-main.js'), CClientScript::POS_END);?>
