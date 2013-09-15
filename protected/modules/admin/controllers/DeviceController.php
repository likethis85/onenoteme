<?php
class DeviceController extends AdminController
{
    public function actionInfo($udid)
    {
        $udid = trim($udid);
        $model = AdminMobileDevice::model()->findByPk($udid);
        
        CVarDumper::dump($model);
    }
}