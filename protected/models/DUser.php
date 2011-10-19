<?php
class DUser extends DModel
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{user}}';
    }

    public function pk()
    {
        return 'id';
    }
    
    public function columns()
    {
        return array('id', 'email', 'name', 'password', 'create_time', 'create_ip', 'state');
    }
}