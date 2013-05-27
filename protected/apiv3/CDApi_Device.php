<?php
class CDApi_Device extends ApiBase
{
    public function create()
    {
        $this->requirePost();
        $this->requiredParams(array('device_udid', 'device_model', 'sys_name', 'sys_version', 'app_version'));
        $params = $this->filterParams(array('device_udid', 'device_model', 'sys_name', 'sys_version', 'app_version', 'device_name', 'language', 'country', 'device_token'));
        
        $udid = $params['device_udid'];
        $device = MobileDevice::model()->findByPk($udid);
        if ($device === null) {
            $device = new MobileDevice();
            $device->udid = $udid;
            $device->model = $params['device_model'];
            $device->sys_name = $params['sys_name'];
            $device->language = $params['language'];
            $device->country = $params['country'];
            $device->push_token = $params['device_token'];
            $device->push_enabled = (int)(bool)$params['device_token'];
            $attributes = null;
        }
        else {
            $device->sys_version = $params['sys_version'];
            $device->app_version = $params['app_version'];
            $device->name = $params['device_name'];
            $device->last_time = time();
            $device->connect_count += 1;
            $attributes = array('last_time', 'connect_count', 'sys_version', 'app_version', 'name');
        }
        
        if ($device->save(true, $attributes)) {
            $this->saveDeviceConnectHistory();
            return $device->attributes;
        }
        else {
            $errors = self::joinModelErrors($device);
            throw new CDApiException(ApiError::DEVICE_SAVE_ERROR, $errors);
        }
    }
    
    public function update_push_token()
    {
        $this->requirePost();
        $this->requiredParams(array('device_udid', 'device_token', 'device_model', 'sys_name', 'sys_version', 'app_version'));
        $params = $this->filterParams(array('device_udid', 'device_model', 'sys_name', 'sys_version', 'app_version', 'device_name', 'language', 'country', 'device_token'));
        
        $udid = $params['device_udid'];
        $device = MobileDevice::model()->findByPk($udid);
        if ($device === null) {
            $device = new MobileDevice();
            $device->udid = $udid;
            $device->model = $params['device_model'];
            $device->sys_name = $params['sys_name'];
            $device->language = $params['language'];
            $device->country = $params['country'];
            $device->push_token = $params['device_token'];
            $device->push_enabled = (int)(bool)$params['device_token'];
            $attributes = null;
        }
        else {
            $device->sys_version = $params['sys_version'];
            $device->app_version = $params['app_version'];
            $device->name = $params['device_name'];
            $device->push_token = $params['device_token'];
            $device->push_enabled = (int)(bool)$params['device_token'];
            $device->last_time = time();
            $device->connect_count += 1;
            $attributes = array('last_time', 'connect_count', 'sys_version', 'app_version', 'name', 'push_token', 'push_enabled');
        }
        
        if ($device->save(true, $attributes)) {
            $this->saveDeviceConnectHistory();
            return $device->attributes;
        }
        else {
            $errors = self::joinModelErrors($device);
            throw new CDApiException(ApiError::DEVICE_SAVE_ERROR, $errors);
        }
    }
    
    /**
     * 更新设备是否接收push信息配置
     * @param string $device_udid
     * @param string $device_token
     * @param integer $push_state
     * @throws CDApiException
     */
    public function update_push_state()
    {
        $this->requirePost();
        $this->requiredParams(array('device_udid', 'device_token', 'push_state'));
        $params = $this->filterParams(array('device_udid', 'device_token', 'push_state'));
        
        $udid = $params['device_udid'];
        $device = MobileDevice::model()->findByPk($udid);
        if ($device === null)
            throw new CDApiException(ApiError::DEVICE_NOT_EXIST);
        else {
            $device->push_enabled = (int)$params['push_state'];
            $device->last_time = time();
            $device->connect_count += 1;
            $attributes = array('last_time', 'connect_count', 'push_enabled');
        }
        
        if ($device->save(true, $attributes)) {
            $this->saveDeviceConnectHistory();
            return $device->attributes;
        }
        else {
            $errors = self::joinModelErrors($device);
            throw new CDApiException(ApiError::DEVICE_SAVE_ERROR, $errors);
        }
    }
}



