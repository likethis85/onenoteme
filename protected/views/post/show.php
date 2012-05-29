<div class="fleft cd-container">
	<div class="panel panel20 post-detail">
		<div class="content-block post-content">
		    <p><?php echo $post->content;?></p>
		    <?php if ($post->tags):?><div class="post-tags">标签：<?php echo $post->tagLinks;?></div><?php endif;?>
        </div>
        <?php if ($post->bmiddlePic):?><div class="content-block post-picture"><?php echo l(CHtml::image($post->bmiddlePic, $post->title), aurl('post/originalpic', array('id'=>$post->id)), array('target'=>'_blank'));?></div><?php endif;?>
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
            <!-- JiaThis Button BEGIN -->
            <div id="jiathis_style_32x32">
            <a class="jiathis_button_qzone"></a>
            <a class="jiathis_button_tsina"></a>
            <a class="jiathis_button_tqq"></a>
            <a class="jiathis_button_renren"></a>
            <a class="jiathis_button_kaixin001"></a>
            <a href="http://www.jiathis.com/share?uid=1622045" class="jiathis jiathis_txt jiathis_separator jtico jtico_jiathis" target="_blank"></a>
            </div>
            <script type="text/javascript" >
            var jiathis_config={
            	data_track_clickback:true,
            	summary:"",
            	ralateuid:{
            		"tsina":"1639121454"
            	},
            	appkey:{
            		"tsina":"456860706",
            		"tqq":"801092195",
            		"t163":"Cwn0IrjtEY6KHD6m",
            		"tsouhu":"dze98woifnwQGH4wN0QL"
            	},
            	hideMore:true
            }
            </script>
            <script type="text/javascript" src="http://v2.jiathis.com/code/jia.js?uid=1622045" charset="utf-8"></script>
            <!-- JiaThis Button END -->
        </div>
        <div class="clear"></div>
        <form action="<?php echo aurl('comment/create');?>" method="post" class="content-block comment-form" id="comment-form">
            <input type="hidden" name="postid" value="<?php echo $post->id;?>" />
            <textarea name="content" id="comment-content" class="mini-content fleft">请输入评论内容。。。</textarea>
            <input type="button" id="submit-comment" value="发表" class="button site-bg fright" />
            <div class="clear"></div>
            <span class="counter">140</span>
            <div class="save-caption-loader hide"></div>
        </form>
        <div id="caption-error" class="content-block hide"></div>
        
        <?php $this->renderPartial('/comment/list', array('comments'=>$comments, 'pages'=>$pages));?>
	</div>
</div>

<div class="fright cd-sidebar">
    <div class="panel panel15 next-posts">
        <div class="post-nav">
            <?php if ($prevUrl):?><a href="<?php echo $prevUrl;?>" class="fleft site-bg button">上一个</a><?php endif;?>
            <?php if ($nextUrl):?><a href="<?php echo $nextUrl;?>" class="fleft site-bg button">下一个</a><?php endif;?>
            <a href="<?php echo $returnUrl;?>" class="fright site-bg button">返回列表</a>
            <div class="clear"></div>
        </div>
        <?php foreach ((array)$nextPosts as $next):?>
        <?php if ($next->channel_id != CHANNEL_DUANZI):?>
        <div class="thumb">
            <?php echo $next->getThumbnailLink('_self');?>
        </div>
        <?php endif;?>
        <?php endforeach;?>
        <div class="clear"></div>
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
});
</script>

