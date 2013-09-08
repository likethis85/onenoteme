<?php
class AdController extends RestController
{
    public function filters()
    {
        return array(
            'postOnly + click',
        );
    }
    
    public function actionClick()
    {
        $model = new AppUnionLog();
        $model->device_udid = $this->deviceUDID;
        $model->wdz_version = $this->appVersion;
        $model->mac_address = $_POST['mac_address'];
        $model->app_store_id = $_POST['app_store_id'];
        $model->app_version = $_POST['app_version_code'];
        $model->bundle_identifier = $_POST['bundle_identifier'];
        $model->platform = $_POST['platform'];
        $model->provider = $_POST['provider'];
        $model->promoter = $_POST['promoter'];
        
        try {
            $result = (int)$model->save();
            $data = array('success' => (int)$result);
        } catch (Exception $e) {
            $data = array('success' => $result, 'message'=>$e->getMessage());
        }
        
        $this->output($data);
    }
}