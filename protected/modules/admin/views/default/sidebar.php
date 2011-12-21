<h4>段子管理</h4>
<ul>
    <li><a href="<?php echo url('admin/post/verify');?>" target="main">审核</a></li>
    <li><a href="<?php echo url('admin/post/today');?>" target="main">今天</a></li>
    <li><a href="<?php echo url('admin/post/search');?>" target="main">搜索</a></li>
    <li><a href="<?php echo url('admin/comment/index');?>" target="main">评论</a></li>
    <li><a href="<?php echo url('admin/tag/index');?>" target="main">标签</a></li>
</ul>

<h4>用户管理</h4>
<ul>
    <li><a href="<?php echo url('admin/user/verify');?>" target="main">审核</a></li>
    <li><a href="<?php echo url('admin/user/search');?>" target="main">查询</a></li>
</ul>

<ul>
    <li><a href="<?php echo app()->homeUrl;?>" target="_blank">网站</a></li>
    <li><a href="<?php echo url('admin/default/logout');?>" target="main">退出</a></li>
</ul>