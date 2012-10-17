<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo app()->charset;?>" />
<title><?php echo app()->name;?>管理中心</title>
<link rel="stylesheet" type="text/css" href="<?php echo sbu('admin/css/cd-admin.css');?>" />
</head>
<frameset cols="130, *" border="0" frameborder="0">
    <frame src="<?php echo aurl('admin/default/sidebar');?>" noresize="noresize" name="sidebar" class="menu-sidebar" scrolling="no" />
    <frame src="<?php echo aurl('admin/default/welcome');?>" name="main" />
    <noframes>
    <body>
    <p>This page uses frames. The current browser you are using does not support frames.</p>
    </body>
    </noframes>
</frameset>
</html>