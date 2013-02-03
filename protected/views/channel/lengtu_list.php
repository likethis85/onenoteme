<div class="fleft cd-container">
    <?php $this->renderPartial($list_view, array('models'=>$models, 'pages'=>$pages));?>
</div>
<div class="fright cd-sidebar">
    <!-- 详情页侧边栏广告位开始 -->
    <?php $this->widget('CDAdvert', array('solt'=>'sidebar_post_detail_first'));?>
    <!-- 详情页侧边栏广告位结束 -->
    <?php $this->widget('CDAdvert', array('solt'=>'channel_home_sidebar_01'));?>
	<div class="panel panel15"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
    <?php $this->widget('CDAdvert', array('solt'=>'channel_home_sidebar_02'));?>
</div>
<div class="clear"></div>
