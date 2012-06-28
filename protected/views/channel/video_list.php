<div class="fleft cd-container">
    <?php foreach ((array)$models as $model):?>
	<div class="panel panel20 video-detail">
		<div class="content-block post-content">
		    <p><?php echo l($model->filterContent, $model->url, array('target'=>'_blank'));?></p>
		    <?php if ($model->tags):?><div class="post-tags">标签：<?php echo $model->tagLinks;?></div><?php endif;?>
        </div>
        <?php if ($model->videoHtml):?><div class="content-block video-player"><?php echo $model->videoHtml;?></div><?php endif;?>
        <div class="toolbar radius3px">
            <div class="content-block info">
                评分:<span id="score-count"><?php echo $model->score;?></span>&nbsp;&nbsp;
                浏览:<span id="view-count"><?php echo (int)$model->view_nums;?></span>&nbsp;&nbsp;
                喜欢:<span id="like-count"><?php echo (int)$model->up_score;?></span>
            </div>
        </div>
	</div>
	<?php endforeach;?>
	<div id="page-nav" class="pages radius3px"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'header'=>'', 'footer'=>''));?></div>
</div>

<div class="fright cd-sidebar">
    <div class="cdc-block">
        <script type="text/javascript">
        alimama_pid="mm_12551250_2904829_10392377";
        alimama_width=300;
        alimama_height=250;
        </script>
        <script src="http://a.alimama.cn/inf.js" type="text/javascript"></script>
    </div>
	<div class="panel panel15"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
</div>
<div class="clear"></div>


<script type="text/javascript">
$(function(){
	var commentInitVal = $('#comment-content').val();
    $('.post-detail').on('click', '.post-arrows a', Waduanzi.RatingPost);
    $('.post-detail').on('click', '.comment-arrows a', Waduanzi.RatingComment);
    Waduanzi.AjustImgWidth($('.post-picture img'), 600);
    $('.post-detail').on('focus', '#comment-content', function(event){
        $(this).addClass('expand');
        if ($.trim($(this).val()) == commentInitVal)
            $(this).val('');
    });
    $('.post-detail').on('blur', '#comment-content', function(event){
        if ($.trim($(this).val()).length == 0) {
            $(this).val(commentInitVal);
            $(this).removeClass('expand');
        }
    });
    $('.post-detail').on('click', '#submit-comment', Waduanzi.PostComment);

    
	var container = $('#comments');
	container.infinitescroll({
    	navSelector: '#page-nav',
    	nextSelector: '#page-nav .next a',
    	itemSelector: '.comment-item',
    	dataType: 'html',
    	infid: 0,
    	loading: {
    		finishedMsg: '已经载入全部内容。',
    		msgText: '正在载入更多内容。。。',
    		img: '<?php echo sbu('images/loading1.gif');?>'
    	}
    },
    function(newElements) {
        var newElems = $(newElements).css({opacity:0});
        newElems.animate({opacity:1});
        container.masonry('appended', newElems, true);

        if (count >= 2) {
        	$(window).unbind('.infscr');
        	$(document).on('click', '#manual-load', function(event){
                container.infinitescroll('retrieve');
                return false;
      	    });
        	$('#manual-load').show();
            count = 0;
        }
        else
            count++;
    });
    
    $(document).ajaxError(function(event, xhr, opt) {
    	if (xhr.status == 404) $('div.pages').remove();
	});
	
});
</script>

<?php cs()->registerScriptFile(sbu('libs/jquery.infinitescroll.min.js'), CClientScript::POS_END);?>


