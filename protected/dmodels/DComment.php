<?php

/**
 * This is the model class for table "{{comment}}".
 *
 * The followings are the available columns in table '{{comment}}':
 * @property integer $id
 * @property integer $post_id
 * @property string $content
 * @property integer $user_id
 * @property string $user_name
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $state
 */
class DComment extends DModel
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    
    /**
     * Returns the static model of the specified DModel class.
	 * @return DComment the static model class
     */
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{comment}}';
    }

    public function pk()
    {
        return 'id';
    }
    
    public function columns()
    {
        return array('id', 'post_id', 'content', 'user_id', 'user_name', 'create_time', 'create_ip');
    }
    
    public function getCommentUserName()
    {
        return $this->user_name ? $this->user_name : user()->guestName;
    }
}