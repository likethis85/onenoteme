<?php
class DeviceController extends RestController
{
    public function filters()
    {
        return array(
            'postOnly + update, pushstate',
        );
    }
    
    public function actionUpdate()
    {
        $model = request()->getPost('model');
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
        $device->sys_name = $this->osName;
        $device->app_version = $this->appVersion;
        $device->model = $model;
        $device->name = $deviceName;
        $device->language = $language;
        $device->country = $country;
        
        $data = array('success' => (int)$device->save());
        $this->output($data);
    }
    
    public function actionPushState()
    {
        $state = (int)(bool)request()->getPost('state');
        $device = RestMobileDevice::model()->findByPk($this->deviceUDID);
        if ($device === null)
            $data = array('success' => 0);
        else {
            $device->push_enabled = $state;
            $result = $device->save(true, array('push_enabled'));
            $data = array('success' => (int)$result, 'state'=>$state);
        }
        
        $this->output($data);
    }
}