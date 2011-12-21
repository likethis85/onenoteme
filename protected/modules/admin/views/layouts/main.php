<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB18030" />
<title>舜网通行证管理中心</title>
</head>
<body>
<div class="main-container"><?php echo $content;?></div>
</body>
</html>

<?php cs()->registerCssFile(sbu('css/admin.css'));?>
<?php cs()->registerCssFile(sbu('libs/bootstrap.min.css'));?>
<?php cs()->registerScriptFile(sbu('js/jquery-1.7.min.js'), CClientScript::POS_BEGIN);?>
<?php cs()->registerScriptFile(sbu('js/admin.js'), CClientScript::POS_END);?>