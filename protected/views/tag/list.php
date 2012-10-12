<h2 class="cd-catption">所有标签· · · · · · </h2>
<div class="panel panel20">
	<div class="tag-list">
	<?php foreach($tags as $key => $tag):?>
	    <a href="<?php echo $tag->getUrl();?>" target="_blank" class="tag-level1"><?php echo $tag->name;?></a>
    <?php endforeach;?>
    </div>
</div>