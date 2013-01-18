<form action="<?php echo aurl('comment/create');?>" method="post" class="comment-form radius4px">
    <input type="hidden" name="postid" value="<?php echo $postid;?>" />
    <textarea name="content" class="comment-content mini-content fleft radius3px" data-placeholder="请输入评论内容。。。">请输入评论内容。。。</textarea>
    <input type="button" value="发表" class="submit-comment button site-bg fright" />
    <div class="clear"></div>
    <span class="counter">140</span>
    <div class="save-caption-loader hide"></div>
</form>
<div class="caption-error content-block hide"></div>

<script type="text/javascript">
$(function(){
    $(document).on('focus', 'textarea.comment-content', function(event){
    	var tthis = $(this);
    	tthis.addClass('expand');
        if ($.trim(tthis.val()) == tthis.attr('data-placeholder'));
            tthis.val('');
    });
    $(document).on('blur', 'textarea.comment-content', function(event){
        var tthis = $(this);
        if ($.trim(tthis.val()).length == 0) {
        	tthis.val(tthis.attr('data-placeholder'));
        	tthis.removeClass('expand');
        }
    });
    $(document).on('click', 'input.submit-comment', Waduanzi.PostComment);
});
</script>