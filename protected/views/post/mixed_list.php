
<div id="waterfall-container">
    <?php foreach ((array)$models as $key => $model):?>
    <div class="waterfall-item">
        <?php if ($model->bmiddle):?><?php echo CHtml::image($model->bmiddle, $model->title, array('class'=>'item-pic'));?><?php endif;?>
        <p><?php echo $model->content;?></p>
        <div class="post-time"><?php echo $model->createTime;?></div>
    </div>
    <?php endforeach;?>
    <div class="clear"></div>
</div>

<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'header'=>'', 'footer'=>''));?></div>

<script type="text/javascript">
$(function(){
    var container = $('#waterfall-container');
    container.imagesLoaded(function(){
        $(this).masonry({
            itemSelector : '.waterfall-item'
        });
    });

    $('#waterfall-container').on('hover', '.waterfall-item', function(event){
        $(this).toggleClass('item-bg');
    });
});
</script>

