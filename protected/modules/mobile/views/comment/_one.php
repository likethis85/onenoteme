<?php foreach ((array)$comments as $comment):?>
<dl class="beta-comment-item">
    <dt class="beta-post-extra"><strong><?php echo $comment->authorName;?></strong>&nbsp;发表于&nbsp;<?php echo $comment->createTime;?></dt>
    <dd class="beta-comment-content"><?php echo $comment->filterContent;?></dd>
    <dd class="beta-comment-toolbar">
        <?php if (!$post->disable_comment):?>
        <a class="beta-comment-reply" href="javascript:void(0);" data-url="<?php echo $comment->replyUrl;?>" rel="nofollow">回复</a>
        <?php endif;?>
        <a class="beta-comment-rating" href="javascript:void(0);" data-url="<?php echo $comment->supportUrl;?>" rel="nofollow">支持(<span class="beta-comment-join-nums"><?php echo $comment->up_nums;?></span>)</a>
        <a class="beta-comment-rating" href="javascript:void(0);" data-url="<?php echo $comment->againstUrl;?>" rel="nofollow">反对(<span class="beta-comment-join-nums"><?php echo $comment->down_nums;?></span></a>
        <a class="beta-comment-rating" href="javascript:void(0);" data-url="<?php echo $comment->reportUrl;?>" rel="nofollow">举报</a>
    </dd>
</dl>
<?php endforeach;?>