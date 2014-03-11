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
    public $apiKey;
    public $secretKey;
    public $timestamp;

    public $deviceUDID;
    public $osVersion;
    public $osName;
    public $appVersion;
    public $userToken;
    
    public function init()
    {
        parent::init();

        $this->processQueryParams();
        
        $headers = getallheaders();
        $this->deviceUDID = $headers['DEVICE-UDID'];
        $this->osVersion = $headers['OS-VERSION'];
        $this->osName = $headers['OS-NAME'];
        $this->appVersion = $headers['APP-VERSION'];
        $this->userToken = $headers['USER-TOKEN'];
        
//         $this->saveDeviceConnectHistory();
    }

    /**
     * 用户ID
     * @return Ambigous <NULL, number> 用户注册返回ID，否则返回null
     */
    public function getUserID()
    {
        $uid = 0;
        if ($this->getUser())
            $uid = $this->getUser()->id;
        
        return $uid;
    }
    
    /**
     * 用户
     * @return RestUser|null 如果没有登录返回null，如果登录并且token一致返回对应的user，否则返回null
     */
    public function getUser()
    {
        static $user = false;
        if ($user === false) {
            if (empty($this->userToken))
                $user = null;
            else {
                $device = $this->getCurrentDevice();
                if (empty($device))
                    $user = null;
                else
                    $user = $device->user;
            }
        }
        return $user;;
    }
    
    public function getCurrentDevice()
    {
        static $device = false;
        if ($device === false)
            $device = RestMobileDevice::model()->findByPk($this->deviceUDID);
        
        return $device;
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
    
    protected function output($data, $expire = 0)
    {
        $data = CJSON::encode($data);
        if ($expire > 0) {
            $get = $_GET;
            unset($get['sig'], $get['timestamp']);
            $cacheID = md5($this->route . var_export($get, true));
            redis()->set($cacheID, $data, $expire);
        }
        
        $this->outputData($data);
    }

    protected function saveDeviceConnectHistory()
    {
        $history = new DeviceConnectHistory();
        $history->device_udid = $this->deviceUDID;
        $history->sys_version = $this->osVersion;
        $history->app_version = $this->appVersion;
        $history->apikey = $this->apiKey;
        $history->method = $this->_apiparams['method'];
        $history->format = $this->_apiparams['format'];
    
        return $history->save() ? $history : false;
    }

    protected function beforeAction($action)
    {
        // 因为sig和timestamp是每个请求都不一样的，所以删掉这两个元素
        $get = $_GET;
        unset($get['sig'], $get['timestamp']);
        
        $cacheID = md5($this->route . var_export($get, true));
        $data = redis()->get($cacheID);
        if ($data === false)
            return true;
        else {
            $this->outputData($data);
            return false;
        }
    }

    protected function outputData($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo $data;
        exit(0);
    }

    protected function processQueryParams()
    {
        $this->processApiKey();

        $this->timestamp = (int)$_GET['timestamp'];
    }

    protected function processApiKey()
    {
        $this->apiKey = h($_GET['apikey']);
    }

}


