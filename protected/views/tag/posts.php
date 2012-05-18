<div class="fleft cd-container">
    <div class="panel panel20">
    	<h2 class="cd-catption">与<?php echo $tagname;?>相关的段子· · · · · · </h2>
        <?php $this->renderPartial('/post/text_list', array('models'=>$models, 'pages'=>$pages));?>
    </div>
</div>

<div class="fright cd-sidebar">
	<div class="panel panel15"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
</div>
<div class="clear"></div>