<div class="fleft cd-container">
    <?php $this->renderPartial($list_view, array('models'=>$models, 'pages'=>$pages, 'channel'=>$this->channel));?>
</div>
<div class="fright cd-sidebar">
    <?php $this->widget('CDAdvert', array('solt'=>'channel_home_sidebar_01'));?>
	<div class="panel panel15 bottom15px"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
    <?php $this->widget('CDAdvert', array('solt'=>'channel_home_sidebar_02'));?>
    <!-- 最新女神图 开始 -->
    <?php $this->widget('CDPostSearch', array('title'=>'最新女神', 'channel'=>CHANNEL_GIRL));?>
    <!-- 最新女神图 结束 -->
</div>
<div class="clear"></div>
