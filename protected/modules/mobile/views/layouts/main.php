<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $this->pageTitle;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="stylesheet" type="text/css" href="<?php echo sbu('libs/bootstrap/css/bootstrap.min.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo tbu('styles/cd-mobile.css?t=20121116001');?>" />
</head>
<body>
<noscript><div id="noscript"><h2>Notice</h2><p>JavaScript is currently off.</p><p>Turn it on in browser settings to view this mobile website.</p></div></noscript>
<header>
    <h1><a href="<?php echo $this->homeUrl;?>"><?php echo app()->name;?></a></h1>
</header>
<div class="beta-container">
<?php echo $content;?>
</div>
<!-- 首页侧边栏广告位2 开始 -->
<div class="admob">
    <?php $this->widget('CDAdvert', array('solt'=>'mobile_banner'));?>
</div>
<!-- 首页侧边栏广告位2 结束 -->
<footer class="clearfix">
    <a class="return-top" href="#top">返回顶部</a>
    <a class="switch-version" href="<?php echo aurl('site/index', array('f'=>1));?>">切换到桌面版</a>
</footer>
<?php echo param('tongji_code');?>
<script src="http://a.tbcdn.cn/apps/top/x/sdk.js?appkey=21351161"></script>
</body>
</html>

<?php cs()->registerCoreScript('jquery');?>
<?php cs()->registerScriptFile(sbu('libs/bootstrap/js/bootstrap.min.js'), CClientScript::POS_END);?>
<?php cs()->registerScriptFile(tbu('scripts/beta-mobile.js?t=20121116001'), CClientScript::POS_END);?>
