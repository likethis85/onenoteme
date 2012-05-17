<div class="pic-list">
    <?php foreach ((array)$models as $key => $model):?>
    <div class="pic-item">
        <a href="<?php echo aurl('post/show', array('id'=>$model->id));?>" target="_blank"><?php echo CHtml::image($model->picture, $model->title);?></a>
    </div>
    <?php endforeach;?>
    <div class="clear"></div>
</div>
<?php if ($pages->pageCount > 1):?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
<?php endif;?>

