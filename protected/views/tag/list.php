<div class="panel panel20">
	<h2 class="cd-catption">热门标签· · · · · · </h2>
	<div class="tag-list">
	<?php foreach($tags as $key => $tag):?>
	    <a href="<?php echo $tag->getUrl();?>" target="_blank" class="tag-level1"><?php echo $tag->name;?></a>
    <?php endforeach;?>
    </div>
</div>