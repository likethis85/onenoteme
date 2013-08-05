<?php
class UserconfigController extends RestController
{
    public function filters()
    {
        return array(
            'postOnly + update',
        );
    }
    
    public function actionUpdate()
    {
        $userID = (int)request()->getPost('user_id');
        $configs = request()->getPost('AppUserConfig');
        
        foreach ($configs as $name => $value) {
            $attributes = array('config_name' => $name);
            if ($userID > 0)
                $attributes['user_id'] = $userID;
            else
                $attributes['device_udid'] = $this->deviceUDID;
            
            $config = AppUserConfig::model()->findByAttributes($attributes);
            if ($config === null) {
                $config = new AppUserConfig();
                $config->device_udid = $this->deviceUDID;
                $config->user_id = $userID;
                $config->config_name = $name;
                $config->config_value = $value;
            }
            else {
                $config->user_id = $userID;
                $config->config_value = $value;
            }
            try {
                $config->save();
            }
            catch (Exception $e) {
                continue;
            }
        }
        
        $this->output(array('success' => 1));
    }
}