<?php
class DComment extends DModel
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    
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
}