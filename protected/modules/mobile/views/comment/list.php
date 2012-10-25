<div class="beta-comments">
    <div class="alert beta-alert beta-alert-message" id="beta-comment-message" data-dismiss="alert"><a class="close" href="javascript:void(0);">&times;</a><span class="text"></span></div>
    <div class="beta-mini-title" id="beta-comment-list"><?php echo t('comment_list');?></div>
    <?php foreach ((array)$comments as $key => $comment):?>
    <dl class="beta-comment-item">
        <dt class="beta-post-extra"><?php echo t('mobile_comment_extra', 'mobile', array('{author}'=>$comment->authorName, '{time}'=>$comment->createTime));?></dt>
        <dd class="beta-comment-content"><?php echo $comment->filterContent;?></dd>
        <dd class="beta-comment-toolbar">
            <?php if (!$post->disable_comment):?>
            <a class="beta-comment-reply" href="javascript:void(0);" data-url="<?php echo $comment->replyUrl;?>" rel="nofollow"><?php echo t('reply_comment');?></a>
            <?php endif;?>
            <a class="beta-comment-rating" href="javascript:void(0);" data-url="<?php echo $comment->supportUrl;?>" rel="nofollow"><?php echo t('support_comment', 'main', array($comment->up_nums));?></a>
            <a class="beta-comment-rating" href="javascript:void(0);" data-url="<?php echo $comment->againstUrl;?>" rel="nofollow"><?php echo t('against_comment', 'main', array($comment->down_nums));?></a>
            <a class="beta-comment-rating" href="javascript:void(0);" data-url="<?php echo $comment->reportUrl;?>" rel="nofollow"><?php echo t('report_comment');?></a>
        </dd>
    </dl>
    <?php endforeach;?>
    
    <?php if (count($comments) === 0):?>
    <div class="beta-no-comments"><?php echo t('have_no_comments');?></div>
    <?php endif;?>
</div>

<?php if ($post->comment_nums > count($comments)):?>
<button data-page="1" id="load-more-comments" type="button" data-toggle="toggle" data-url="<?php echo $post->commentsUrl;?>" class="btn btn-block btn-inverse"><?php echo t('load_more_comments', 'mobile');?></button>
<?php endif;?>

<script type="text/javascript">
$(function(){
	$(document).on('click', '.beta-comment-rating', BetaComment.rating);
	$(document).on('click', '.beta-comment-reply', BetaComment.reply);
	$(document).on('click', '#load-more-comments', BetaComment.loadMore);
});
</script>