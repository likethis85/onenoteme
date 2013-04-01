<div class="fleft cd-container">
    <h2 class="cd-caption">与标签“<?php echo $fallTitle;?>”相关的内容</h2>
    <?php $this->renderPartial('/post/line_list', array('models'=>$models, 'pages'=>$pages));?>
</div>
<div class="fright cd-sidebar">
    <?php $this->widget('CDAdvert', array('solt'=>'posts_list_sidebar_01'));?>
	<div class="panel panel15 bottom15px"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
	<?php $this->widget('CDAdvert', array('solt'=>'posts_list_sidebar_02'));?>
    <!-- 最新趣图 开始 -->
    <?php $this->widget('CDPostSearch', array('title'=>'最新趣图', 'channel'=>CHANNEL_LENGTU));?>
    <!-- 最新趣图 结束 -->
</div>
<div class="clear"></div>


