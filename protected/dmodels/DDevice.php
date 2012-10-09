<?php

/**
 * This is the model class for table "{{token}}".
 *
 * The followings are the available columns in table '{{topic}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $device_token
 */
class DDevice extends DModel
{
    /**
     * Returns the static model of the specified DModel class.
	 * @return DTag the static model class
     */
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{device}}';
    }

    public function pk()
    {
        return 'id';
    }
    
    public function columns()
    {
        return array('id', 'user_id', 'device_token', 'last_time');
    }

}