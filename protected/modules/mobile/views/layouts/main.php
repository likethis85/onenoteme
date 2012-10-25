<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $this->pageTitle;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="stylesheet" type="text/css" href="<?php echo sbu('libs/bootstrap/css/bootstrap.min.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo tbu('styles/beta-mobile.css');?>" />
</head>
<body>
<noscript><div id="noscript"><h2>Notice</h2><p>JavaScript is currently off.</p><p>Turn it on in browser settings to view this mobile website.</p></div></noscript>
<header>
    <a href="<?php echo $this->homeUrl;?>"><?php echo app()->name;?></a>
</header>
<div class="beta-container">
<?php echo $content;?>
</div>
<footer class="clearfix">
    <a class="return-top" href="#top"><?php echo t('return_top', 'mobile');?></a>
    <a class="switch-version" href="<?php echo url('site/index', array('f'=>1));?>"><?php echo t('switch_desktop_version', 'mobile');?></a>
</footer>
</body>
</html>

<?php cs()->registerCoreScript('jquery');?>
<?php cs()->registerScriptFile(sbu('libs/bootstrap/js/bootstrap.min.js'), CClientScript::POS_END);?>
<?php cs()->registerScriptFile(tbu('scripts/beta-mobile.js'), CClientScript::POS_END);?>
