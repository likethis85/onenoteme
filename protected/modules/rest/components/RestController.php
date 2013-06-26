<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *
 * @param RestUser $user
 * @param MobileDevice $device
 */
class RestController extends CController
{
    public $deviceUDID;
    public $osVersion;
    public $appVersion;
    
    public function init()
    {
        parent::init();
        
        $headers = getallheaders();
        $this->deviceUDID = $headers['DEVICE_UDID'];
        $this->osVersion = $headers['OS_VERSION'];
        $this->appVersion = $headers['APP_VERSION'];
        
//         $this->saveDeviceConnectHistory();
    }

    /**
     * 用户ID
     * @return Ambigous <NULL, number> 用户注册返回ID，否则返回null
     */
    public function getUserID()
    {
        $uid = null;
        if ($this->getUser())
            $uid = $this->getUser()->id;
        
        return $uid;
    }
    
    /**
     * 用户
     * @return RestUser
     */
    public function getUser()
    {
        static $user;
        if ($user === null)
            $user = 0;
        
        return $user;;
    }
    
    public function getDevice()
    {
        
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
    
    protected function output($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo CJSON::encode($data);
        exit(0);
    }

    protected function saveDeviceConnectHistory()
    {
        $history = new DeviceConnectHistory();
        $history->device_udid = $this->deviceUDID;
        $history->sys_version = $this->osVersion;
        $history->app_version = $this->appVersion;
        $history->apikey = $this->_apiparams['apikey'];
        $history->method = $this->_apiparams['method'];
        $history->format = $this->_apiparams['format'];
    
        return $history->save() ? $history : false;
    }
}


