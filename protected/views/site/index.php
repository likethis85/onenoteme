<div class="panel panel15 bottom15px best-posts">
    <div class="fleft best-left">
        <h4>编辑推荐</h4>
        <?php $this->widget('CDPostSearch', array('channel'=>CHANNEL_FUNNY, 'recommend'=>true, 'mediaType'=>MEDIA_TYPE_TEXT, 'count' => 9, 'duration'=>600, 'trace'=>'recommend', 'view'=>CDPostSearch::VIEW_RECOMMEND_TEXT));?>
        <?php $this->widget('CDPostSearch', array('channel'=>CHANNEL_FUNNY, 'recommend'=>true, 'mediaType'=>MEDIA_TYPE_IMAGE, 'count'=>5, 'duration'=>600, 'trace'=>'recommend', 'view'=>CDPostSearch::VIEW_RECOMMEND_IMAGE));?>
    </div>
    <div class="fright best-right">
        <h4>专题推荐</h4>
        <ul class="author-list">
            <li><span class="hot">1</span><a href="<?php echo aurl('tag/posts', array('name'=>'邪恶漫画'));?>">邪恶漫画</a></li>
            <li><span class="hot">2</span><a href="<?php echo aurl('tag/posts', array('name'=>'暴走漫画'));?>">暴走漫画</a></li>
            <li><span class="hot">3</span><a href="<?php echo aurl('tag/posts', array('name'=>'色系军团'));?>">色系军团</a></li>
            <li><span>4</span><a href="<?php echo aurl('tag/posts', array('name'=>'郭斯特'));?>">郭斯特</a></li>
            <li><span>5</span><a href="<?php echo aurl('tag/posts', array('name'=>'夫妻笑话'));?>">夫妻笑话</a></li>
            <li><span>6</span><a href="<?php echo aurl('tag/posts', array('name'=>'高帅富VS屌丝'));?>">高帅富VS屌丝</a></li>
            <li><span>7</span><a href="<?php echo aurl('tag/posts', array('name'=>'大象ROV'));?>">大象ROV</a></li>
            <li><span>8</span><a href="<?php echo aurl('tag/posts', array('name'=>'罗罗布'));?>">罗罗布</a></li>
            <li><span>9</span><a href="<?php echo aurl('tag/posts', array('name'=>'内涵漫画'));?>">内涵漫画</a></li>
            <li><span>10</span><a href="<?php echo aurl('tag/posts', array('name'=>'彼格梨'));?>">彼格梨</a></li>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div class="fleft cd-container">
<?php $this->renderPartial('/post/line_list', array('models' => $models, 'pages' => $pages));?>
</div>
<div class="fright cd-sidebar">
    <!-- 首页侧边栏广告位1 开始 -->
    <?php $this->widget('CDAdvert', array('solt'=>'home_sidebar_01', 'bizrule'=>$this->showAdvert));?>
    <!-- 首页侧边栏广告位1 结束 -->
    
    <div class="cdc-block cd-border app-list">
        <a href="http://itunes.apple.com/cn/app//id486268988?mt=8" target="_blank" title="挖段子iPhone应用, 最新版本 2.2.2"><img src="<?php echo sbu('images/app_ios.png');?>" alt="挖段子iPhone应用" /></a>
        <a href="<?php echo sbu('android/waduanzi.apk');?>" target="_blank" title="挖段子Andoird应用 最新版本 1.1.0"><img src="<?php echo sbu('images/app_android.png');?>" alt="挖段子Andoird应用" /></a>
        <a href="<?php echo CDBaseUrl::mobileHomeUrl();?>" target="_blank" title="挖段子手机版网站"><img src="<?php echo sbu('images/mobile_site.png');?>" alt="挖段子手机版网站" /></a>
    </div>
    
    <div class="panel panel10 bottom15px">
        <iframe width="270" height="250" frameborder="0" scrolling="no" src="http://app.wumii.com/ext/widget/hot?prefix=http%3A%2F%2Fwww.waduanzi.com%2F&num=10&t=1"></iframe>
    </div>
    <!-- 首页侧边栏广告位2 开始 -->
    <?php $this->widget('CDAdvert', array('solt'=>'home_sidebar_02', 'bizrule'=>$this->showAdvert));?>
    <!-- 首页侧边栏广告位2 结束 -->
    <div class="panel panel15 bottom15px"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
    <!-- 首页侧边栏广告位3 开始 -->
    <?php $this->widget('CDAdvert', array('solt'=>'home_sidebar_03', 'bizrule'=>$this->showAdvert));?>
    <!-- 首页侧边栏广告位3 结束 -->
</div>
<div class="clear"></div>

<?php $this->widget('CDLinks', array('ishome'=>1, 'count'=>40));?>


