<div class="fleft cd-container">
	<div class="panel panel25 post-detail">
		<h1><?php echo $post->title;?></h1>
		<div class="post-user"><?php echo $post->PostUserName . '&nbsp;' . $post->createTime;?></div>
		<?php if ($post->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $post->tagsLinks;?></div><?php endif;?>
		<div class="content" id="content">
		    <?php echo $post->content;?>
		    <?php if ($post->picture) echo '<br />' . CHtml::image($post->picture, $post->title, array('class'=>'item-pic'));?>
		</div>
	</div>
</div>

<div class="fright cd-sidebar">
	<div class="panel panel15"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
</div>
<div class="clear"></div>


