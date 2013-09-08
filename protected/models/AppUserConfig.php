<?php
/**
 * This is the model class for table "{{app_user_config}}".
 *
 * The followings are the available columns in table '{{app_user_config}}':
 * @property integer $id
 * @property string $device_udid
 * @property integer $user_id
 * @property string $config_name
 * @property string $config_value
 * @property integer $config_type
 * @property string $name
 * @property string $desc
 */
class AppUserConfig extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return AppUserConfig the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return TABLE_APP_USER_CONFIG;
    }
    
    public function rules()
    {
        return array(
            array('device_udid, name, config_name', 'required'),
            array('config_name', 'unique'),
            array('config_name', 'match', 'pattern'=>'/^[a-z][\w\d\_]{4,99}/i', 'message'=>'参数名只能是字母数字下划线组合，且只能以字母开头'),
            array('user_id, config_type', 'numerical', 'integerOnly'=>true),
            array('config_name', 'length', 'max'=>100),
            array('config_value, desc', 'safe'),
        );
    }
    
    public function relations()
    {
        return array();
    }
    
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'device_udid' => '设置UDID',
            'user_id' => '绑定用户ID',
            'config_name' => '参数变量名',
            'config_value' => '参数值',
            'name' => '参数名称',
            'desc' => '描述',
        );
    }
    
    public static function fetchDeviceAllConfig()
    {
        
    }
}


