<a name="comment-list"></a>
<?php foreach ($models as $model):?>
<li><?php echo $model->content;?></li>
<?php endforeach;?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages' => $pages));?></div>

