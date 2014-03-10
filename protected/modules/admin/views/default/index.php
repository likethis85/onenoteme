<div class="hero-unit">
    <h2>欢迎使用<?php echo app()->name;?>管理中心</h2>
    <p><?php echo date('Y-m-d H:i:s');?></p>
    <p>
        有&nbsp;<b><?php echo $unverifyPostCount;?></b>&nbsp;个投稿未审核。
        <a class="btn btn-primary btn-small" href="<?php echo url('admin/post/verify');?>">查看投稿</a>
    </p>
    <p>
        有&nbsp;<b><?php echo $hidePostCount;?></b>&nbsp;个段子待显示。
        <a class="btn btn-primary btn-small" href="<?php echo url('admin/post/latest', array('state'=>POST_STATE_DISABLED));?>">查看列表</a>
    </p>
    <p>
        有&nbsp;<b><?php echo $commentCount;?></b>&nbsp;个评论未审核。
        <a class="btn btn-primary btn-small" href="<?php echo url('admin/comment/verify');?>">审核评论</a>
    </p>
    <p>
        有&nbsp;<b><?php echo $userCount;?></b>&nbsp;个用户注册请求未处理。
        <a class="btn btn-primary btn-small" href="<?php echo url('admin/user/verify');?>">审核用户</a>
    </p>
    <hr />
    <h3>微博开放平台：</h3>
    <ul>
        <li>新浪微博AccessToken：<?php echo redis()->get('sina_weibo_access_token');?></li>
        <li>图床微博AccessToken：<?php echo redis()->get('sina_weibo_image_store_access_token');?></li>
        <li>腾讯微博AccessToken：<?php echo redis()->get('qq_weibo_access_token');?></li>
    </ul>
</div>