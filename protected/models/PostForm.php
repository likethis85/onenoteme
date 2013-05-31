<?php
class PostForm extends CFormModel
{
    public $title;
    public $channel_id = 0;
    public $content;
    public $image;
    
    public function rules()
    {
        return array(
            array('title, content', 'required', 'on'=>'text'),
            array('image, content', 'required', 'on'=>'image'),
            array('channel_id', 'numerical', 'integerOnly'=>true),
			array('content', 'safe'),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'title' => '标题',
            'channel_id' => '频道',
            'content' => '内容',
            'image' => '图片',
        );
    }
}