<?php foreach ((array)$comments as $comment):?>
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