<div class="alert beta-alert beta-alert-message" id="beta-create-message" data-dismiss="alert"><a class="close" href="javascript:void(0);">&times;</a><span class="text"></span></div>
<?php echo CHtml::form(aurl('mobile/comment/create'),  'post', array('class'=>'beta-comment-form', 'id'=>'comment-form'));?>
    <?php echo CHtml::activeHiddenField($comment, 'post_id');?>
    <span class="help-block <?php if ($comment->hasErrors()) echo 'error';?>">评论内容不能少于<?php echo param('commentMinLength');?>个字哦～～～></span>
    <?php echo CHtml::activeTextArea($comment, 'content', array('class'=>'comment-content', 'minlen'=>param('commentMinLength')));?>
    <button type="submit" class="btn btn-block btn-primary" data-toggle="toggle" data-loading-text="发布中...">提交</button>
<?php echo CHtml::endForm();?>

<script type="text/javascript">
$(function(){
	$(document).on('submit', '.beta-comment-form', BetaComment.create);
	$(document).on('blur', '.beta-comment-form .comment-content', BetaComment.contentValidate);
});
</script>


