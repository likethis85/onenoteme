<div class="beta-comments">
    <div class="alert beta-alert beta-alert-message" id="beta-comment-message" data-dismiss="alert"><a class="close" href="javascript:void(0);">&times;</a><span class="text"></span></div>
    <div class="beta-mini-title" id="beta-comment-list">评论列表</div>
    <?php foreach ((array)$comments as $key => $comment):?>
    <dl class="beta-comment-item">
        <dt class="beta-post-extra"><strong><?php echo $comment->authorName;?></strong>&nbsp;发表于&nbsp;<?php echo $comment->createTime;?></dt>
        <dd class="beta-comment-content"><?php echo $comment->filterContent;?></dd>
        <dd class="beta-comment-toolbar">
            <a class="beta-comment-reply" href="javascript:void(0);" data-url="<?php echo $comment->replyUrl;?>" rel="nofollow">回复</a>
            <a class="beta-comment-rating" href="javascript:void(0);" data-url="<?php echo $comment->supportUrl;?>" rel="nofollow">支持(<span class="beta-comment-join-nums"><?php echo $comment->up_score;?></span>)</a>
            <a class="beta-comment-rating" href="javascript:void(0);" data-url="<?php echo $comment->againstUrl;?>" rel="nofollow">反对(<span class="beta-comment-join-nums"><?php echo $comment->down_score;?></span>)</a>
        </dd>
    </dl>
    <?php endforeach;?>
    
    <?php if (count($comments) === 0):?>
    <div class="beta-no-comments">当前暂无评论</div>
    <?php endif;?>
</div>

<?php if ($post->comment_nums > count($comments)):?>
<button data-page="1" id="load-more-comments" type="button" data-toggle="toggle" data-url="<?php echo $post->getCommentUrl();?>" class="btn btn-block btn-inverse">载入更多评论</button>
<?php endif;?>

<script type="text/javascript">
$(function(){
	$(document).on('click', '.beta-comment-rating', BetaComment.rating);
	$(document).on('click', '.beta-comment-reply', BetaComment.reply);
	$(document).on('click', '#load-more-comments', BetaComment.loadMore);
});
</script>