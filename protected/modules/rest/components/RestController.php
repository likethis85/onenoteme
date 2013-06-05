<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class RestController extends CController
{
    public function init()
    {
        parent::init();
//         $this->saveDeviceConnectHistory();
    }
    
    public function filterPutOnly($filterChain)
    {
        if(Yii::app()->getRequest()->getIsPutRequest())
            $filterChain->run();
        else
            throw new CHttpException(400,Yii::t('yii','Your request is invalid.'));
    }
    
    public function filterDeleteOnly($filterChain)
    {
        if(Yii::app()->getRequest()->getIsDeleteRequest())
            $filterChain->run();
        else
            throw new CHttpException(400,Yii::t('yii','Your request is invalid.'));
    }
    
    protected function output($value)
    {
        $data = array(
            'errcode' => 0,
            'response' => $value,
        );
        
        header('Content-Type: application/json; charset=utf-8');
        echo CJSON::encode($data);
        exit(0);
    }

    protected function saveDeviceConnectHistory()
    {
        $history = new DeviceConnectHistory();
        $history->device_udid = $this->_params['device_udid'];
        $history->sys_version = $this->_params['sys_version'];
        $history->app_version = $this->_params['app_version'];
        $history->apikey = $this->_apiparams['apikey'];
        $history->method = $this->_apiparams['method'];
        $history->format = $this->_apiparams['format'];
    
        return $history->save() ? $history : false;
    }
}