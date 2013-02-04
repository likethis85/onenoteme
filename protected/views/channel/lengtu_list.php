<div class="fleft cd-container">
    <?php $this->renderPartial($list_view, array('models'=>$models, 'pages'=>$pages, 'channel'=>$this->channel));?>
</div>
<div class="fright cd-sidebar">
    <?php $this->widget('CDAdvert', array('solt'=>'channel_home_sidebar_01'));?>
	<div class="panel panel15 bottom15px"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
    <?php $this->widget('CDAdvert', array('solt'=>'channel_home_sidebar_02'));?>
</div>
<div class="clear"></div>
