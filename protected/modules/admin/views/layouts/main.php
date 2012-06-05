<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo app()->charset;?>" />
<title><?php echo app()->name;?>管理中心</title>
<link rel="stylesheet" type="text/css" href="<?php echo sbu('libs/bootstrap/css/bootstrap.min.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo sbu('admin/css/cd-admin.css');?>" />
<script type="text/javascript" src="<?php echo sbu('libs/jquery-1.7.2.min.js');?>"></script>
</head>
<body>
<div class="main-container"><?php echo $content;?></div>
</body>
</html>

<?php cs()->registerScriptFile(sbu('admin/js/cd-admin.js'), CClientScript::POS_END);?>
<?php cs()->registerScriptFile(sbu('libs/bootstrap/js/bootstrap.min.js'), CClientScript::POS_END);?>