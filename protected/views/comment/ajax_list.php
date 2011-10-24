<div class="comment-list-<?php echo $postid;?>">
    <?php foreach ($models as $model):?>
    <ul class="comment-item">
    	<li><img src="http://www.qiushibaike.com/system/avabak/150036/thumb/UC_Photo_1.jpg" /></li>
    	<li>悲催的名字</li>
    	<li><?php echo $model->content;?></li>
    	<div class="clear"></div>
    </ul>
    <?php endforeach;?>
</div>