<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->pageTitle;?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="onenote.me" />
<meta name="copyright" content="Copyright (c) 2011 onenote.me All Rights Reserved." />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-basic.css');?>" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-mobile.css?v=20111110.02');?>" />
<script type="text/javascript" src="<?php echo sbu('scripts/jquery-1.6.4.min.js');?>"></script>
</head>
<body>
<div class="m-wrapper">
	<h1 class="m-logo"><a href="<?php echo aurl('mobile/index');?>" title="返回挖段子首页">挖段子</a></h1>
</div>
<div class="m-wrapper m-nav">
	<a href="<?php echo aurl('mobile/index');?>">最新</a>&nbsp;|&nbsp;<a href="<?php echo aurl('mobile/week');?>">最热</a>
</div>

<div class="m-wrapper"><?php echo $content;?></div>


<div class="m-wrapper m-footer">
	<p class="fl">版权所有 ©2011 <a href="">onenote.me</a></p><p class="fr">笑死人不尝命&nbsp;<a href="#top">TOP</a></p>
	<div class="clear"></div>
</div>
<?php $this->renderPartial('/public/tongji');?>
</body>
</html>