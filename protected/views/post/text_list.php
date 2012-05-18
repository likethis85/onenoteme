<div class="post-list">
    <?php foreach ((array)$models as $key => $model):?>
    <div class="post-item radius5px">
    	<div class="post-user"><?php echo $model->authorName . '&nbsp;' . $model->createTime;?></div>
        <div class="item-detail">
            <a class="item-link" href="<?php echo $model->url;?>" target="_blank" title="新窗口中查看段子：<?php echo h($model->title);?>">: :</a>
            <span class="item-content">
                <?php echo $model->content;?>
            </span>
            <?php if ($model->bmiddle):?>
            <div class="post-image">
                <span class="image-more">点击图片查看更多精彩～～～</span>
                <?php echo CHtml::image($model->bmiddle, $model->title, array('class'=>'item-pic'));?>
            </div>
            <?php endif;?>
        </div>
        <?php if ($model->tags):?><div class="post-tags"><span class="cgray">标签：</span><?php echo $model->tagLinks;?></div><?php endif;?>
    </div>
    <?php endforeach;?>
</div>

<?php if ($pages->pageCount > 1):?>
<div class="pages"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'header'=>'', 'footer'=>''));?></div>
<?php endif;?>

<script type="text/javascript">
$(function(){
	$('.post-item').hover(
		function(e){$(this).addClass('post-item-hover');},
		function(e){$(this).removeClass('post-item-hover');}
	);

	$('.item-pic').toggle(
		function(e) {
			var offset = $(this).parent().offset();
		    $('body').scrollTop(offset.top);
	        $(this).parent().css('max-height', '9999px');
	    },
	    function(e) {
		    var offset = $(this).parents('.post-item').offset();
		    $('body').scrollTop(offset.top);
	        $(this).parent().css('max-height', '450px');
    	}
	);
});
</script>

