<div class="fl cd-container">
    <ul class="subnav-links category-links">
        <?php foreach ($categories as $category):?>
    	<li><?php echo $category->postLink;?></li>
        <?php endforeach;?>
    </ul>
    <?php $this->renderPartial('list', array('models'=>$models, 'pages'=>$pages));?>
</div>

<div class="fr cd-sidebar">
	<?php $this->widget('CDHotTags', array('title'=>'热门标签'));?>
</div>
<div class="clear"></div>