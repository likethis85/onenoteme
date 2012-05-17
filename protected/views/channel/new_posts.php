<div class="fleft cd-container">
    <?php $this->renderPartial('/post/pic_list', array('models'=>$models, 'pages'=>$pages));?>
</div>

<div class="fright cd-sidebar">
	<?php $this->widget('CDHotTags', array('title'=>'热门标签'));?>
</div>
<div class="clear"></div>