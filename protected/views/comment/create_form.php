<?php if (user()->isGuest):?>
<div class="login-tip">要发表评论，请先<?php echo CHtml::link('登录', user()->loginUrl);?></div>
<?php else:?>
<form action="<?php echo aurl('comment/create');?>" method="post" class="comment-form">
<input type="hidden" name="postid" class="postid" value="<?php echo $postid;?>" />
<table class="tbl-comment">
	<tr>
		<td><textarea name="content" class="ccontent"></textarea></td>
		<td><input type="button" value="发布" class="submit-button" /></td>
		<td class="result-tip"></td>
	</tr>
</table>
</form>
<?php endif;?>