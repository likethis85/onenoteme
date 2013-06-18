<div class="panel panel20 publish-post">
    <?php if (user()->hasFlash('publish_post_success')):?>
    <div class="alert alert-success"><?php echo user()->getFlash('publish_post_success');?></div>
    <?php endif;?>
    <h2>有好段子？一块分享下吧。</h2>
    <?php echo CHtml::form('', 'post', array('class'=>'form-horizontal cd-form post-form', 'enctype'=>'multipart/form-data'));?>
        <div class="control-group <?php echo $model->hasErrors('content') ? 'error' : '';?>">
            <label class="control-label"><?php echo CHtml::activeLabel($model, 'content', array('label'=>'内容<p class="cred">(必填)</p>'));?></label>
            <div class="controls">
                <?php echo CHtml::activeTextArea($model, 'content', array('class'=>'span7', 'rows'=>8, 'tabindex'=>1));?>
                <?php if ($model->hasErrors('content')):?><p class="help-block"><?php echo $model->getError('content');?></p><?php endif;?>
            </div>
        </div>
        
        <div class="control-group <?php echo $model->hasErrors('tags') ? 'error' : '';?>">
            <label class="control-label"><?php echo CHtml::activeLabel($model, 'tags');?></label>
            <div class="controls">
                <?php echo CHtml::activeTextField($model, 'tags', array('class'=>'span5', 'tabindex'=>2));?>
                <?php if ($model->hasErrors('tags')):?>
                    <span class="help-inline"><?php echo $model->getError('tags');?></span>
                <?php else:?>
                    <span class="help-inline cgray">最多允许5个标签，多个标签使用逗号(,)分隔</span>
                <?php endif;?>
            </div>
        </div>
        
        <div class="control-group <?php echo $model->hasErrors('image') ? 'error' : '';?>">
            <label class="control-label"><?php echo CHtml::activeLabel($model, 'image');?></label>
            <div class="controls">
                <?php echo CHtml::activeFileField($model, 'image', array('class'=>'cd-text', 'tabindex'=>3));?>
                <?php if ($model->hasErrors('image')):?><span class="help-inline"><?php echo $model->getError('image');?></span><?php endif;?>
            </div>
        </div>

        <?php if ($model->getEnableCaptcha()):?>
        <div class="control-group <?php echo $model->hasErrors('captcha') ? 'error' : '';?>">
            <label class="control-label"><?php echo CHtml::activeLabel($model, 'captcha');?></label>
            <div class="controls">
                <?php echo CHtml::activeTextField($model, 'captcha', array('class'=>'cd-captcha cd-text', 'tabindex'=>4));?>
                <?php $this->widget('CCaptcha');?>
                <?php if ($model->hasErrors('captcha')):?><span class="help-inline"><?php echo $model->getError('captcha');?></span><?php endif;?>
            </div>
            <div class="clear"></div>
        </div>
        <?php endif;?>

        <div class="form-actions">
            <?php echo CHtml::submitButton('马上发布', array('class'=>'btn btn-primary', 'tabindex'=>5));?>
        </div>
        <?php if (user()->hasFlash('create_post_result')) echo user()->getFlash('create_post_result');?>
    <?php echo CHtml::endForm();?>
    
		<h2>发贴说明</h2>
		<ul class="site-notice cgray">
			<li>禁止发广告贴。文章不得出现广告倾向；文章不得包含网站链接。</li>
			<li>不推荐低俗内容。过于恶心或者私密可能会引起人反感的内容请慎重考虑后再决定发表与否，此类内容的通过率可能会非常低。此类内容包括但不仅限于：过于低俗的粗口、与排泄物有关、与生殖器官有关、与性有关的内容等。</li>
			<li>不推荐敏感内容：与政治、时政、政府、国家制度、国家领导人相关的内容请慎重考虑后再决定发表与否，避免出现对于敏感内容的描述，避免出现对于此类内容的不恰当评论和隐射。</li>
		</ul>
</div>