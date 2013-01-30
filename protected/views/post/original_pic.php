<div class="cd-wrapper acenter">
    <?php $this->widget('CDAdvert', array('solt'=>'original_pic_first'));?>
    <h2><?php echo h($model->title);?></h2>
    <p><?php echo image($model->originalPic, $model->title);?></p>
    <?php $this->widget('CDAdvert', array('solt'=>'original_pic_sencond'));?>
</div>