<?php
class AdminAppUnionLog extends AppUnionLog
{
    /**
     * Returns the static model of the specified AR class.
     * @return AdminAppUnionLog the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function getDeviceUrl()
    {
        return url('admin/device/info');
    }
}