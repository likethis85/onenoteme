<form action="<?php echo aurl('comment/create');?>" method="post" class="content-block comment-form" id="comment-form">
    <input type="hidden" name="postid" value="<?php echo $postid;?>" />
    <textarea name="content" id="comment-content" class="mini-content fleft radius3px">请输入评论内容。。。</textarea>
    <input type="button" id="submit-comment" value="发表" class="button site-bg fright" />
    <div class="clear"></div>
    <span class="counter">140</span>
    <div class="save-caption-loader hide"></div>
</form>
<div id="caption-error" class="content-block hide"></div>

<script type="text/javascript">
$(function(){
	var commentInitVal = $('#comment-content').val();
    $(document).on('focus', '#comment-content', function(event){
        $(this).addClass('expand');
        if ($.trim($(this).val()) == commentInitVal)
            $(this).val('');
    });
    $(document).on('blur', '#comment-content', function(event){
        if ($.trim($(this).val()).length == 0) {
            $(this).val(commentInitVal);
            $(this).removeClass('expand');
        }
    });
    $(document).on('click', '#submit-comment', Waduanzi.PostComment);
});
</script>