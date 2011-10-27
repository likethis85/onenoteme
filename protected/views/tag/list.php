<div class="fl cd-container">
	<h2 class="cd-catption">热门标签· · · · · · </h2>
	<div class="tag-list">
	<?php foreach($tags as $key => $tag):?>
        <?php echo CHtml::link($tag->name, $tag->getUrl(), array('target'=>$target, 'rel'=>'tag', 'class'=>$levels[$key]));?>
    <?php endforeach;?>
    </div>
</div>

<div class="fr cd-sidebar">
	<?php $this->widget('CDHotTags', array('title'=>'热门标签'));?>
</div>
