<?php
class Api_Device extends ApiBase
{
    public function create()
    {
        $this->requirePost();
        $this->requiredParams(array('device_udid', 'device_model', 'sys_name', 'sys_version', 'app_version'));
        $params = $this->filterParams(array('device_udid', 'device_model', 'sys_name', 'sys_version', 'app_version', 'device_name', 'language', 'country'));
        
        $udid = $params['device_udid'];
        $device = MobileDevice::model()->findByAttributes(array('udid'=>$udid));
        if ($device === null) {
            $device = new MobileDevice();
            $device->udid = $udid;
            $device->model = $params['device_model'];
            $device->sys_name = $params['sys_name'];
            $device->language = $params['language'];
            $device->country = $params['country'];
            $device->sys_version = $params['sys_version'];
            $device->app_version = $params['app_version'];
            $device->name = $params['device_name'];
            $attributes = null;
        }
        else {
            $device->last_time = time();
            $device->connect_count += 1;
            $attributes = array('last_time', 'connect_count', 'sys_version', 'app_version', 'device_name');
        }
        
        
        
        
        if ($device->save(true, $attributes)) {
            $this->saveDeviceConnectHistory($device);
            return $device->attributes;
        }
        else {
            $errors = self::joinModelErrors($device);
            
            throw new CDApiException(ApiError::DEVICE_SAVE_ERROR, $errors);
        }
    }
    
    private function saveDeviceConnectHistory(MobileDevice $device)
    {
        $history = new DeviceConnectHistory();
        $history->device_id = $device->id;
        $history->sys_version = $device->sys_version;
        $history->app_version = $device->app_version;
        $history->apikey = $this->_apiparams['apikey'];
        $history->method = $this->_apiparams['method'];
        $history->format = $this->_apiparams['format'];
        
        return $history->save() ? $history : false;
    }
}

/*

&device_udid=testudid2&device_model=iPhone&sys_name=iPhone OS&sys_version=6.1.2&app_version=2.2.2&device_name&language=zh-Hans&country=zh



 */