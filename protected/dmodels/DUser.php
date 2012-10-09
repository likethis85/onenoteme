<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $state
 * @property sting token
 */
class DUser extends DModel
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    const STATE_EDITOR = 150;
    const STATE_ADMIN = 199;
    
    /**
     * Returns the static model of the specified DModel class.
	 * @return DUser the static model class
     */
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
        return array('id', 'email', 'name', 'password', 'create_time', 'create_ip', 'state', 'token');
    }
}