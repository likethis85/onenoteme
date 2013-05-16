<div class="panel panel20">
    <div class="cd-activate">
    <?php if ($errno):?>
        <h4 class="cred"><?php echo $message;?></h4>
    <?php else:?>
        <div class="cgreen">
            <h4>恭喜您，<?php echo $user->getDisplayName();?>&nbsp;您已经完成注册。</h4>
            <p>&gt;&gt;&nbsp;<a href="<?php echo CDBaseUrl::siteHomeUrl();?>">点击返回首页</a></p>
            <p>&gt;&gt;&nbsp;<a href="<?php echo CDBaseUrl::memberHomeUrl();?>">点击返回用户中心</a></p>
        </div>
    <?php endif;?>
    </div>
</div>