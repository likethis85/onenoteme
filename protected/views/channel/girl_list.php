<div class="fleft cd-container">
    <?php $this->renderPartial('/post/grid_list', array('models'=>$models, 'pages'=>$pages));?>
</div>
<div class="fright cd-sidebar">
    <?php $this->widget('CDAdvert', array('solt'=>'channel_sidebar_ad_first'));?>
	<div class="panel panel15"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
</div>
<div class="clear"></div>



