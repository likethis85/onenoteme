<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->pageTitle;?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="waduanzi.com" />
<meta name="copyright" content="Copyright (c) 2011-2012 waduanzi.com All Rights Reserved." />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta content="telephone=no" name="format-detection" />
<link rel="shortcut icon" href="<?php echo sbu('images/favicon.ico');?>" type="image/vnd.microsoft.icon" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-basic.css');?>" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo sbu('styles/cd-mobile.css?v=20120519.01');?>" />
<script type="text/javascript" src="<?php echo sbu('libs/jquery-1.7.2.min.js');?>"></script>
</head>
<body>
<a name="top"></a>
<div class="m-wrapper">
	<h1 class="m-logo"><a href="<?php echo aurl('mobile/index');?>" title="返回挖段子首页">挖段子</a></h1>
</div>
<div class="m-wrapper m-nav">
	<a href="<?php echo aurl('mobile/index');?>">首页</a>
	<a href="<?php echo aurl('mobile/channel', array('id'=>CHANNEL_GIRL));?>">福利</a>
	<a href="<?php echo aurl('mobile/channel', array('id'=>CHANNEL_DUANZI));?>">段子</a>
	<a href="<?php echo aurl('mobile/channel', array('id'=>CHANNEL_LENGTU));?>">冷图</a>
	&nbsp;|&nbsp;
	<a href="<?php echo aurl('mobile/index');?>">最新</a>
</div>
<div class="app-link">
    <a href="itms-apps://itunes.apple.com/cn/app//id486268988?mt=8" target="_blank">iPhone应用 v2.2.1</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="http://s.waduanzi.com/android/waduanzi.apk" target="_blank">Android应用 v1.1.0</a>
</div>
<div class="m-wrapper"><?php echo $content;?></div>
<div class="space10px"></div>
<ul class="admob">
    <li><a target="_blank" href="http://s.click.taobao.com/t_8?e=7HZ6jHSTbIg8q6nQGu%2B62FZKBlLXgG2mwcp6cvy9HYCgmQ%3D%3D&p=mm_12551250_0_0">公猴英伦休闲女鞋休闲鞋女单鞋小白鞋小白皮鞋平底时尚单鞋女051</a></li>
    <li><a target="_blank" href="http://s.click.taobao.com/t_8?e=7HZ6jHSTbIlJ0FqabRsutUHqDnE4%2B79bP24MnZw9DcCG7w%3D%3D&p=mm_12551250_0_0">【倔强的偏执】中长袖连衣裙秋装新款 秋款秋连衣裙OL1518</a></li>
    <li><a target="_blank" href="http://s.click.taobao.com/t_8?e=7HZ6jHSTbIg8oI%2FbUXjChSS8HRuUVdw7bnV4OoAz1vtquA%3D%3D&p=mm_12551250_0_0">淘金币 2012春秋装新款连衣裙韩版V领毛衣裙子 时尚女装修身连衣</a></li>
</ul>
<div class="m-wrapper m-footer">
	<p class="fleft">版权所有 &copy;2011-2012&nbsp;<a href="<?php echo aurl('mobile');?>">waduanzi.com</a></p><p class="fright"><a href="#top">TOP</a></p>
	<div class="clear"></div>
</div>
<script type="text/javascript">
$(function(){
	changeImageSize();
	window.onresize = changeImageSize;
	function changeImageSize()
	{
		var width = parseInt($('.post-item').width());
		$('.post-item img').css('max-width', width);
	}
});
</script>
<?php $this->renderPartial('/public/tongji');?>
</body>
</html>