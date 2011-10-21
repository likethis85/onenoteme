<ul class="subnav-links category-links">
    <?php foreach ($categories as $category):?>
	<li><?php echo $category->postLink;?></li>
    <?php endforeach;?>
</ul>
<br />
<?php foreach ($models as $model):?>
<li><?php echo $model->content;?></li>
<?php endforeach;?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>