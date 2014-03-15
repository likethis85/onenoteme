<div class="hero-unit">
    <h3>微博开放平台：</h3>
    <ul>
        <li>新浪微博AccessToken：<?php echo redis()->get('sina_weibo_access_token');?></li>
        <li>图床微博AccessToken：<?php echo redis()->get('sina_weibo_image_store_access_token');?></li>
        <li>腾讯微博AccessToken：<?php echo redis()->get('qq_weibo_access_token');?></li>
    </ul>
</div>