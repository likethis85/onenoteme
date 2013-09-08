<?php
class MobileCommentForm extends CFormModel
{
    public $post_id;
    public $content;
    public $captcha;
    
    public function rules()
    {
        return array(
            array('post_id, content', 'required'),
            array('post_id', 'numerical', 'integerOnly'=>true),
            array('captcha', 'captcha', 'allowEmpty'=>$this->captchaAllowEmpty()),
			array('content', 'safe'),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'post_id' => '段子ID',
            'content' => '评论内容',
            'captcha' => '验证码',
        );
    }
    
    public function save()
    {
        $comment = new MobileComment();
        $comment->attributes = $this->attributes;
        $comment->user_id = (int)user()->id;
        $comment->state = (int)param('default_mobile_new_comment_state');
        $comment->source = COMMENT_SOURCE_MOBILE_WEB;
        if (!user()->getIsGuest())
            $comment->user_name = (strip_tags(trim(user()->name)));
        
        $comment->save();
        $this->afterSave();
        return $comment;
    }
        
    public function afterSave()
    {
        
    }
    
    public function captchaAllowEmpty()
    {
        return true;
    }
        
}