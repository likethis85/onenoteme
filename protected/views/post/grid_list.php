<div id="tip-block" class="radius5px"></div>
<div class="panel panel10 bottom10px">
    <ul class="fleft hot-keyword">
        <li><span class="cred announce">24小时更新：<?php echo Post::todayUpdateCount();?>篇。&nbsp;&nbsp;&nbsp;QQ群：49401589</span></li>
    </ul>
    <ul class="mode-switch fright">
        <li class="fall"><a href="<?php echo aurl($this->route, array('page'=>(int)$_GET['page'], 's'=>POST_LIST_STYLE_WATERFALL));?>">缩略图</a></li>
        <li class="list on"><a href="<?php echo aurl($this->route, array('page'=>(int)$_GET['page']));?>">列表</a></li>
    </ul>
    <div class="clear"></div>
</div>

<div class="panel panel15 bottom10px post-grid-list clearfix">
    <div id="grid-container">
        <?php foreach ((array)$models as $index => $model):?>
        <div class="grid-item">
            <?php echo $model->thumbnailLink;?>
            <div class="post-tip" style="display:none;"><?php echo $model->getFilterSummary();?></div>
        </div>
        <?php endforeach;?>
    </div>
    <div class="clear"></div>
</div>

<?php if($pages->pageCount > 1):?>
<div class="panel-rect panel-pages" id="page-nav">
    <div class="cd-pages"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
</div>
<?php endif;?>
<div id="loading-box"></div>
<div id="manual-load" class="hide radius5px">载入更多内容</div>

<script type="text/javascript">
$(function(){
	$('#grid-container .grid-item').hover(function(){
		var tthis = $(this);
		var pos = tthis.position();
		var top = pos.top + tthis.height() + 5;
		$('#tip-block').html($(this).children('.post-tip').html());
		var left = pos.left - ($('#tip-block').width() - tthis.width()) / 2;
		$('#tip-block').css('top', top).css('left', left);
	}, function(){
		$('#tip-block').css('top', '-9999px').empty();
	});

	$.getScript('<?php echo sbu('libs/jquery.infinitescroll.min.js');?>', function(){
		var manual = $('#manual-load');
		$('#grid-container').infinitescroll({debug:true,
        	navSelector: '#page-nav',
        	nextSelector: '#page-nav .next a',
        	itemSelector: '.grid-item',
        	animate: false,
        	dataType: 'html',
        	loading: {
            	speed: 0,
            	selector: $('#loading-box'),
            	finishedMsg: '',
        		msgText: '',
        		msg: $('<img src="<?php echo sbu('images/loading2.gif');?>" />')
        	}
    	}, function(newElements, opts) {
    		var tthis = $(this);
    		var page = opts.state.currPage;
    		if (opts.state.currPage == 2) {
        		tthis.infinitescroll('pause');
            	$(document).on('click', '#manual-load', function(event){
            		tthis.infinitescroll('retrieve');
                    return false;
          	    });
            	manual.show();
    		}
    		if (opts.state.isDone) {
    			tthis.infinitescroll('unbind');
    			manual.html('没有更多内容啦！');
    		}
    	});
	});
});
</script>

<?php //cs()->registerScriptFile(sbu('libs/jquery.infinitescroll.min.js'), CClientScript::POS_END);?>

