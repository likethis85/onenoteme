<?php
class DeviceController extends RestController
{
    public function filters()
    {
        return array(
            'postOnly + update',
        );
    }
    
    public function actionUpdate()
    {
        $model = request()->getPost('model');
        $sysname = request()->getPost('sys_name');
        $deviceName = request()->getPost('device_name');
        $language = request()->getPost('language');
        $country = request()->getPost('country');
        
        $device = RestMobileDevice::model()->findByPk($this->deviceUDID);
        if ($device === null) {
            $device = new RestMobileDevice();
            $device->udid = $this->deviceUDID;
            $device->user_id = 0;
        }
        $device->sys_version = $this->osVersion;
        $device->app_version = $this->appVersion;
        $device->model = $model;
        $device->sys_name = $sysname;
        $device->name = $deviceName;
        $device->language = $language;
        $device->country = $country;
        
        $data = array('success' => (int)$device->save());
        $this->output($data);
    }
}