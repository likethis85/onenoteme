<div class="fleft cd-container">
	<div class="panel panel20 post-detail">
		<div class="content-block post-content">
		    <p><?php echo $post->filterContent;?></p>
		    <?php if ($post->tags):?><div class="post-tags">标签：<?php echo $post->tagLinks;?></div><?php endif;?>
        </div>
        <?php if ($post->videoHtml):?>
        <div class="content-block video-player"><?php echo $post->videoHtml;?></div>
        <?php elseif ($post->bmiddlePic):?>
        <div class="content-block post-picture"><?php echo l(CHtml::image($post->bmiddlePic, $post->filterContent . ', ' . $post->getTagText(',')), aurl('post/bigpic', array('id'=>$post->id)), array('target'=>'_blank', 'title'=>$post->filterContent));?></div>
        <?php endif;?>
        <!-- 详情内容下方广告位 -->
        <div class="toolbar radius3px">
    		<div class="content-block post-arrows fleft">
                <a class="site-bg arrow-up" data-id="<?php echo $post->id;?>" data-value="1" data-url="<?php echo aurl('post/score');?>" href="javascript:void(0);">喜欢</a>
                <a class="site-bg arrow-down" data-id="<?php echo $post->id;?>" data-value="0" data-url="<?php echo aurl('post/score');?>" href="javascript:void(0);">讨厌</a>
                <div class="clear"></div>
            </div>
            <div class="content-block info fleft">
                评分:<span id="score-count"><?php echo $post->score;?></span>&nbsp;&nbsp;
                浏览:<span id="view-count"><?php echo (int)$post->view_nums;?></span>&nbsp;&nbsp;
                喜欢:<span id="like-count"><?php echo (int)$post->up_score;?></span>
            </div>
            <div class="content-block social fright">
                <!-- Baidu Button BEGIN -->
                <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare">
                    <a class="bds_qzone"></a>
                    <a class="bds_tsina"></a>
                    <a class="bds_tqq"></a>
                    <a class="bds_renren"></a>
                    <a class="bds_douban"></a>
                    <span class="bds_more"></span>
                </div>
                <!-- Baidu Button END -->
            </div>
            <div class="clear"></div>
        </div>
        <?php if ($post->bmiddlePic):?>
        <div class="content-block wumii-box">
            <script type="text/javascript" id="wumiiRelatedItems"></script>
        </div>
        <?php endif;?>
        <a name="comments"></a>
        <form action="<?php echo aurl('comment/create');?>" method="post" class="content-block comment-form" id="comment-form">
            <input type="hidden" name="postid" value="<?php echo $post->id;?>" />
            <textarea name="content" id="comment-content" class="mini-content fleft radius3px">请输入评论内容。。。</textarea>
            <input type="button" id="submit-comment" value="发表" class="button site-bg fright" />
            <div class="clear"></div>
            <span class="counter">140</span>
            <div class="save-caption-loader hide"></div>
        </form>
        <div id="caption-error" class="content-block hide"></div>
        <div id="comments">
        <?php $this->renderPartial('/comment/list', array('comments'=>$comments, 'pages'=>$pages));?>
        </div>
        <div class="content-block cd-border">
            <script type="text/javascript">
                (function(){
                var url = "http://widget.weibo.com/distribution/comments.php?width=0&url=auto&ralateuid=1639121454&appkey=2981913360&dpc=1";
                url = url.replace("url=auto", "url=" + document.URL);
                document.write('<iframe id="WBCommentFrame" src="' + url + '" scrolling="no" frameborder="0" style="width:100%"></iframe>');
                })();
            </script>
            <script src="http://tjs.sjs.sinajs.cn/open/widget/js/widget/comment.js" type="text/javascript" charset="utf-8"></script>
            <script type="text/javascript">
            window.WBComment.init({
                "id": "WBCommentFrame"
            });
            </script>
        </div>
	</div>
</div>

<div class="fright cd-sidebar">
    <div class="panel panel10 next-posts">
        <div class="post-nav">
            <?php if ($prevUrl):?><a href="<?php echo $prevUrl;?>" class="fleft site-bg button">上一个</a><?php endif;?>
            <?php if ($nextUrl):?><a href="<?php echo $nextUrl;?>" class="fleft site-bg button">下一个</a><?php endif;?>
            <a href="<?php echo $returnUrl;?>" class="fright site-bg button">返回列表</a>
            <div class="clear"></div>
        </div>
        <?php foreach ((array)$nextPosts as $next):?>
        <?php if ($next->channel_id != CHANNEL_DUANZI && $next->channel_id != CHANNEL_VIDEO):?>
        <div class="thumb">
            <?php echo $next->getThumbnailLink('_self');?>
        </div>
        <?php endif;?>
        <?php endforeach;?>
        <div class="clear"></div>
    </div>
    <?php if ($post->bmiddlePic):?>
    <!-- 详情页侧边栏广告位开始 -->
    <div class="cdc-block cd-border ad-block">
        <script type="text/javascript">
        alimama_pid="mm_12551250_2904829_10392377";
        alimama_width=300;
        alimama_height=250;
        </script>
        <script src="http://a.alimama.cn/inf.js" type="text/javascript"></script>
    </div>
    <!-- 详情页侧边栏广告位结束 -->
    <?php endif;?>
	<div class="panel panel15"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
</div>
<div class="clear"></div>

<script type="text/javascript">
    var wumiiPermaLink = '<?php echo $post->url;?>';
    var wumiiTitle = '<?php echo json_encode($post->filterContent);?>';
    var wumiiTags = '<?php echo json_encode($post->getTagText(','));?>';
    var wumiiSitePrefix = "http://www.waduanzi.com/";
    var wumiiParams = "&num=5&mode=2&pf=JAVASCRIPT";
</script>
<script type="text/javascript" src="http://widget.wumii.com/ext/relatedItemsWidget"></script>
<a href="http://www.wumii.com/widget/relatedItems" style="border:0;">
    <img src="http://static.wumii.com/images/pixel.png" alt="无觅相关文章插件，快速提升流量" style="border:0;padding:0;margin:0;" />
</a>

<script type="text/javascript">
$(function(){
	var postid = <?php echo $post->id;?>;
	Waduanzi.IncreasePostViewNums(postid, '<?php echo aurl('post/views');?>');
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


