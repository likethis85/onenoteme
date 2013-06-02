<?php
class PostForm extends CFormModel
{
    public $title;
    public $channel_id = 0;
    public $content;
    public $tags;
    public $image;
    public $captcha;
    
    public function rules()
    {
        return array(
            array('content', 'required'),
            array('channel_id', 'numerical', 'integerOnly'=>true),
			array('content', 'safe'),
            array('image', 'file', 'allowEmpty'=>true),
            array('tags', 'length', 'max'=>50),
            array('captcha', 'captcha', 'allowEmpty'=>!$this->getEnableCaptcha()),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'title' => '标题',
            'channel_id' => '频道',
            'content' => '内容',
            'image' => '图片',
            'tags' => '标签',
            'captcha' => '验证码',
        );
    }
    
    public function save()
    {
        $post = new Post();
        $post->channel_id = CHANNEL_FUNNY;
        $post->content = $this->content;
        $post->tags = $this->tags;
        $post->title = $this->title;
    }
    
    public function getEnableCaptcha()
    {
        return user()->getIsGuest();
    }
}