
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->pageTitle;?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="onenote.me" />
<meta name="copyright" content="Copyright (c) 2011 onenote.me All Rights Reserved." />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/me-basic.css');?>" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/me-main.css');?>" />
<script type="text/javascript" src="<?php echo sbu('scripts/jquery-1.6.4.min.js');?>"></script>
</head>
<body>
<div class="me-wrapper me-header">
	<ul>
		<li><a href="<?php echo aurl('post/latest');?>">最新</a></li>
		<li><a href="<?php echo aurl('post/tu')?>">糗图</a></li>
		<li><a href="<?php echo aurl('post/hour');?>">瞅瞅</a></li>
		<li><a href="<?php echo aurl('tag/list');?>">标签</a></li>
		<li><a href="<?php echo aurl('post/vote');?>">鉴定</a></li>
		<li><a href="<?php echo aurl('post/create');?>">加料</a></li>
	</ul>
</div>
<div class="me-wrapper">
	<?php echo $content;?>
    <div class="clear"></div>
</div>
<div class="me-wrapper me-footer">
	footer
</div>
</body>
</html>