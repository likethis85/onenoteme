<?php
class Api_Device extends ApiBase
{
    public function token()
    {
        self::requirePost();
        $this->requiredParams(array('device_token'));
        $params = $this->filterParams(array('device_token'));
        
        $token = trim($params['device_token']);
        $token = trim($token, '<>');
        $token = str_replace(' ', '', $token);
    
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
}