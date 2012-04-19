<?php
class Api_Device extends ApiBase
{
    public function token()
    {
        self::requirePost();
        $this->requiredParams(array('device_token'));
        $params = $this->filterParams(array('device_token'));
        
        $token = Device::convertToken($params['device_token']);
    
        if (empty($token))
            $data = array('errno'=>-1);
        else {
            $model = Device::model()->findByAttributes(array('device_token'=>$token));
            if ($model === null) {
                $model = new Device();
                $model->device_token = $token;
                $model->uuid = '';
                $model->last_time = $_SERVER['REQUEST_TIME'];
                $result = (int)!$model->save();
            }
            else
                $result = 2;
    
            $data = array('errno'=>$result);
        }
        
        return $data;
    }
    
    public function pushstate(/*$device_token, $state*/)
    {
        self::requirePost();
        $this->requiredParams(array('device_token', 'state'));
        $params = $this->filterParams(array('device_token', 'state'));
        
        $token = Device::convertToken($params['device_token']);
        $state = (int)$params['state'];
        
        if (empty($token))
            $result = -1;
        else {
            $model = Device::model()->findByAttributes(array('device_token'=>$token));
            if ($model === null) {
                $result = -2;
            }
            else {
                $model->close_push = $state ? CD_YES : CD_NO;
                $result = (int)!$model->save(true, array('close_push'));
            }
        }
        
        $data = array('errno'=>$result);
        return $data;
    }
}