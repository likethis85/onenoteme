<div class="panel panel20">
	<h2 class="cd-catption">热门标签· · · · · · </h2>
	<div class="tag-list">
	<?php foreach($tags as $key => $tag):?>
        <?php echo CHtml::link($tag->name, $tag->getUrl(), array('target'=>$target, 'rel'=>'tag', 'class'=>'tag-level1'));?>
    <?php endforeach;?>
    </div>
</div>