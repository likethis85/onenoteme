<div class="fl cd-container">
	<h2 class="cd-catption">与<?php echo $tagname;?>相关的段子· · · · · · </h2>
    <?php $this->renderPartial('/post/list', array('models'=>$models, 'pages'=>$pages));?>
</div>

<div class="fr cd-sidebar">
	<?php $this->widget('CDHotTags', array('title'=>'热门标签'));?>
</div>
<div class="clear"></div>