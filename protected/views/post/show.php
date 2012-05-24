<div class="fleft cd-container">
	<div class="panel panel20 post-detail">
		<div class="content-block post-content">
		    <p><?php echo $post->content;?></p>
		    <?php if ($post->tags):?><div class="post-tags">标签：<?php echo $post->tagLinks;?></div><?php endif;?>
        </div>
        <?php if ($post->bmiddle):?><div class="content-block post-picture"><?php echo CHtml::image($post->bmiddle, $post->title);?></div><?php endif;?>
		<div class="content-block arrow fleft">
            <a class="site-bg arrow-up" href="#"></a>
            <a class="site-bg arrow-down" href="#"></a>
            <div class="clear"></div>
        </div>
        <div class="content-block info fleft">
            ffffff
        </div>
        <div class="content-block social fright">sadfasd</div>
        <div class="clear"></div>
	</div>
</div>

<div class="fright cd-sidebar">
	<div class="panel panel15"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
</div>
<div class="clear"></div>


