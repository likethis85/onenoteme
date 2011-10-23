<ul class="post-list">
    <?php foreach ($models as $model):?>
    <li>
        <div><?php echo $model->content;?></div>
        <p><?php echo $model->tagsLinks;?></p>
    </li>
    <?php endforeach;?>
</ul>

<?php if ($pages && ($pages instanceof CPagination)):?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
<?php endif;?>
