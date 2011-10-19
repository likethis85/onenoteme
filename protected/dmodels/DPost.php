<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property integer $category_id
 * @property integer $topic_id
 * @property string $title
 * @property string $content
 * @property integer $create_time
 * @property integer $comment_nums
 * @property integer $state
 */
class DPost extends DModel
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    const STATE_TOP = 2;
    
    /**
     * Returns the static model of the specified DModel class.
	 * @return DPost the static model class
     */
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{post}}';
    }
    
    public function pk()
    {
        return 'id';
    }
    
    public function columns()
    {
        return array('id', 'category_id', 'topic_id', 'title', 'content', 'create_time', 'comment_nums', 'state');
    }
}