<div class="fleft cd-container">
	<div class="panel panel20 post-detail">
		<div class="content-block post-content">
		    <p><?php echo $post->content;?></p>
		    <?php if ($post->tags):?><div class="post-tags">标签：<?php echo $post->tagLinks;?></div><?php endif;?>
        </div>
        <?php if ($post->bmiddle):?><div class="content-block post-picture"><?php echo CHtml::image($post->bmiddle, $post->title);?></div><?php endif;?>
		<div class="content-block arrow fleft">
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
            	hideMore:true
            }
            </script>
            <script type="text/javascript" src="http://v2.jiathis.com/code/jia.js?uid=1622045" charset="utf-8"></script>
            <!-- JiaThis Button END -->
        </div>
        <div class="clear"></div>
        <div class="content-block comments">
            <form action="#" method="post">
                <textarea name="content" class="content"></textarea>
                <input type="submit" id="post-comment" value="发表" />
            </form>
        </div>
	</div>
</div>

<div class="fright cd-sidebar">
	<div class="panel panel15"><?php $this->widget('CDHotTags', array('title'=>'热门标签'));?></div>
</div>
<div class="clear"></div>

<script type="text/javascript">
$(function(){
    $('.post-detail').on('click', '.arrow a', Waduanzi.RatingPost);
});
</script>

