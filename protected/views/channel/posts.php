<div class="fleft cd-container">
    <div class="panel"><?php $this->renderPartial('/post/list', array('models'=>$models, 'pages'=>$pages));?></div>
</div>

<div class="fright cd-sidebar">
	<div class="panel"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
</div>
<div class="clear"></div>