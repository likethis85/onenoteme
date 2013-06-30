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
            array('tags', 'checkTags'),
            array('captcha', 'captcha', 'allowEmpty'=>!$this->getEnableCaptcha()),
        );
    }
    
    public function checkTags($attribute, $params)
    {
        $value = $this->$attribute;
        if (empty($value)) return true;
        
        $tags = explode(',', $value);
        if (count($tags) > 5) {
            $this->addError($attribute, '标签最多允许5个');
        }
        else
            return true;
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
        $post->content = CDBase::convertPunctuation($this->content);
        $post->tags = $this->tags;
        $post->title = CDBase::convertPunctuation($this->title);
        $post->state = user()->checkAccess('author') ? POST_STATE_DISABLED : POST_STATE_UNVERIFY;
        $post->user_id = user()->id;
        $post->user_name = user()->name;
        $post->up_score = mt_rand(param('init_up_score_min'), param('init_up_score_max'));
        $post->down_score = mt_rand(param('init_down_score_min'), param('init_down_score_max'));
        $post->view_nums = mt_rand(param('init_view_nums_min'), param('init_view_nums_max'));
        $post->homeshow = CD_NO;
        
        $this->image = CUploadedFile::getInstance($this, 'image');
        $post->media_type = $this->image ? MEDIA_TYPE_IMAGE : MEDIA_TYPE_TEXT;
        
        if ($this->image && $uploadedImage = $this->beforeSave()) {
            if ($uploadedImage) {
                $post->original_pic = $uploadedImage['url'];
                $post->original_width = $uploadedImage['width'];
                $post->original_height = $uploadedImage['height'];
                $post->content = '<p>' . $post->getMiddleImage() . '</p>' . $post->content;
            }
            else {
                $this->addError('image', '上传图片错误');
                return false;
            }
        }
        
        if ($post->save()) {
            $this->afterSave($post);
            return $post;
        }else {
            $error = $post->getErrors();
            $this->addErrors('content', '保存出错');
            return false;
        }
    }
    
    public function beforeSave()
    {
        $upload = CUploadedFile::getInstance($this, 'image');
        if ($upload === null) return null;
        
        $result = CDUploadedFile::saveImage(true, $upload->getTempName(), '', '', array('water_position'=>CDWaterMark::POS_BOTTOM_LEFT));
        return $result ? $result : false;
    }
    
    public function afterSave($post)
    {
        
    }
    
    public function getEnableCaptcha()
    {
        return user()->getIsGuest();
    }

    protected function beforeValidate()
    {
        $tags = str_replace('，', ',', $this->tags);
        $tags = explode(',', $tags);
        $tags = array_map('trim', $tags);
        $tags =  array_unique(array_filter($tags));
        $this->tags = join(',', $tags);
        
        return true;
    }
}

