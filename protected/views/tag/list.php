<div class="fl cd-container">
	<div class="tag-list">
	<?php foreach($tags as $key => $tag):?>
        <?php echo CHtml::link($tag->name, $tag->getUrl(), array('target'=>$target, 'rel'=>'tag', 'class'=>$levels[$key]));?>
    <?php endforeach;?>
    </div>
</div>

<div class="fr cd-sidebar">
	<div class="content-block">
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
		这是一个内容块<br />
	</div>
</div>
