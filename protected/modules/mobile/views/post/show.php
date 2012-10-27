<div class="beta-post-detail">
    <div class="beta-title">
        <h1 class="post-title"><?php echo $post->titleLink;?></h1>
        <span class="comment-number"><?php echo l($post->comment_nums, $post->url, array('title'=>$post->title));?></span>
    </div>
    <p class="post-extra"><?php echo $post->authorName;?>&nbsp;|&nbsp;<?php echo $post->shortTime;?></p>
    <div id="beta-post-content">
        <?php echo $post->filterContent;?>
        <?php if ($post->bmiddlePic) echo image($post->bmiddlePic, $post->title, array('class'=>'bmiddle'));?>
    </div>
    <div class="beta-create-form"><?php $this->renderPartial('/comment/_create_form', array('comment'=>$comment));?></div>
    <?php $this->renderPartial('/comment/list', array('comments'=>$comments, 'post'=>$post));?>
</div>

<div class="hide ajax-jsstr">
    <span class="ajax-send">发送数据中...</span>
    <span class="ajax-fail">请求错误.</span>
    <span class="ajax-rules-invalid">请输入评论内容和验证码后再发布</span>
    <span class="ajax-has-joined">您已经参与过了，谢谢</span>
</div>

<script type="text/javascript">
$(function(){
	BetaPost.increaseVisitNums(<?php echo $post->id;?>, '<?php echo aurl('mobile/post/views');?>');
});
</script>

<?php cs()->registerCoreScript('cookie');?>
<?php cs()->registerScriptFile(sbu('libs/json.js'), CClientScript::POS_END);?>