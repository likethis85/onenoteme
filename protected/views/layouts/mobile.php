<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->pageTitle;?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="waduanzi.com" />
<meta name="copyright" content="Copyright (c) 2011-2012 waduanzi.com All Rights Reserved." />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta content="telephone=no" name="format-detection" />
<link rel="shortcut icon" href="<?php echo sbu('images/favicon.ico');?>" type="image/vnd.microsoft.icon" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-basic.css');?>" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-mobile.css?v=20111110.02');?>" />
<script type="text/javascript" src="<?php echo sbu('libs/jquery-1.7.1.min.js');?>"></script>
</head>
<body>
<a name="top"></a>
<div class="m-wrapper">
	<h1 class="m-logo"><a href="<?php echo aurl('mobile/index');?>" title="返回挖段子首页">挖段子</a></h1>
</div>
<div class="m-wrapper m-nav">
	<span class="list-title"><?php echo $this->subtitle;?></span>&nbsp;&nbsp;
	<a href="<?php echo aurl('mobile/index');?>">最新</a>&nbsp;|&nbsp;<a href="<?php echo aurl('mobile/week');?>">最热</a>
</div>

<div class="m-wrapper"><?php echo $content;?></div>


<div class="m-wrapper m-footer">
	<p class="fl">版权所有 &copy;2011 <a href="<?php echo aurl('mobile');?>">waduanzi.com</a></p><p class="fr">笑死人不尝命&nbsp;<a href="#top">TOP</a></p>
	<div class="clear"></div>
</div>
<?php $this->renderPartial('/public/tongji');?>
</body>
</html>