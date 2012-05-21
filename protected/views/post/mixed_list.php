<div id="waterfall-container">
    <?php foreach ((array)$models as $key => $model):?>
    <div class="waterfall-item">
        <div class="post-time"><?php echo $model->createTime;?></div>
        <?php if ($model->bmiddle):?><div class="pic-block"><?php echo $model->bmiddleLink;?></div><?php endif;?>
        <p><?php echo l($model->content, $model->url, array('target'=>'_blank'));?></p>
    </div>
    <?php endforeach;?>
</div>
<div class="pages hide"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'header'=>'', 'footer'=>''));?></div>

<script type="text/javascript">
$(function(){
    var container = $('#waterfall-container');
    container.imagesLoaded(function(){
    	container.masonry({
            itemSelector: '.waterfall-item'
        });
    });

    container.infinitescroll({
    	navSelector: 'div.pages',
    	nextSelector: 'div.pages .next a',
    	itemSelector: '.waterfall-item',
    	dataType: 'html',
    	loading: {
    		finishedMsg: '数据载入成功',
    		msgText: '正在载入更多内容'
    	}
    },
    function(newElements) {
        var newElems = $(newElements).css({opacity:0});
        newElems.imagesLoaded(function(){
            newElems.animate({opacity:1});
            container.masonry('appended', newElems, true);
        });
    	
    });

    $('#waterfall-container').on('hover', '.waterfall-item', function(event){
        $(this).toggleClass('item-bg');
    });
});
</script>

<?php cs()->registerScriptFile(sbu('libs/jquery.masonry.min.js'), CClientScript::POS_END);?>
<?php cs()->registerScriptFile(sbu('libs/jquery.infinitescroll.min.js'), CClientScript::POS_END);?>