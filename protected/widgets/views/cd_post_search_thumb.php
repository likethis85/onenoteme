<div class="panel panel10 thumb-posts">
<?php if ($this->title):?>
    <h3 class="content-title"><?php echo $this->title;?></h3>
<?php endif;?>
<?php foreach ((array)$models as $model):?>
    <div class="thumb">
        <?php echo $model->getSquareThumbLink($this->linkTarget, 0, $this->trace);?>
    </div>
    <?php endforeach;?>
    <div class="clear"></div>
</div>
