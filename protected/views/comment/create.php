<form action="<?php echo aurl('comment/create');?>" method="post" class="comment-form radius4px">
    <input type="hidden" name="postid" value="<?php echo $postid;?>" />
    <textarea name="content" class="comment-content mini-content fleft radius3px" data-placeholder="请输入评论内容。。。">请输入评论内容。。。</textarea>
    <input type="button" value="发表" class="submit-comment button site-bg fright" />
    <div class="clear"></div>
    <span class="counter">140</span>
    <div class="save-caption-loader hide"></div>
</form>
<div class="caption-error content-block hide"></div>

