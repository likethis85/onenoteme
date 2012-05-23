<div class="fleft cd-container">
	<div class="panel panel20 post-detail">
		<h1><?php echo $post->title;?></h1>
		<?php if ($post->bmiddle):?><div class="post-picture"><?php echo CHtml::image($post->bmiddle, $post->title);?></div><?php endif;?>
		<div class="post-content"><?php echo $post->content;?></div>
		<?php if ($post->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $post->tagLinks;?></div><?php endif;?>
	</div>
</div>

<div class="fright cd-sidebar">
	<div class="panel panel15"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
</div>
<div class="clear"></div>


