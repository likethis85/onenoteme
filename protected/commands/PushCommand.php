<?php

class PushCommand extends CConsoleCommand
{
    public function init()
    {
        $sdk = Yii::getPathOfAlias('application.libs.baidupush') . DS . 'Channel.class.php';
        require($sdk);
    }
    
    public function actionTest()
    {
        $channel = new Channel(BAIDU_APP_WDZ_APP_KEY, BAIDU_APP_WDZ_SECRET_KEY);
        $channel->setHost(Channel::HOST_IOS_DEV);
//         $optional[Channel::USER_ID] = '924028076706842347';
        $optional[Channel::DEVICE_TYPE] = BAIDU_DEVICE_TYPE_IOS;
        $optional[Channel::MESSAGE_TYPE] = BAIDU_MESSAGE_TYPE_ALERT;
        $message = array(
            'aps' => array(
                'alert' => 'Message From Baidu Push',
                'Sound' => '',
                'Badge' => 0
            ),
        );
        
        $ret = $channel->pushMessage (Channel::PUSH_TO_ALL, $message, 'test_key', $optional);
        
        if (false === $ret) {
            echo 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!';
            echo 'ERROR NUMBER: ' . $channel->errno ();
            echo 'ERROR MESSAGE: ' . $channel->errmsg ();
            echo 'REQUEST ID: ' . $channel->getRequestId ();
        }
        else
        {
            echo 'SUCC, ' . __FUNCTION__ . ' OK!!!!!';
            echo 'result: ' . print_r($ret, true);
        }
    }
    
    public function actionDeltag()
    {
        $channel = new Channel(BAIDU_APP_WDZ_APP_KEY, BAIDU_APP_WDZ_SECRET_KEY);
        $channel->setHost(Channel::HOST_IOS_DEV);
    
        $ret1 = $channel->deleteTag('has_logined');
        $ret2 = $channel->deleteTag('not_logined');
    
        if (false === $ret1) {
            echo 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!';
            echo 'ERROR NUMBER: ' . $channel->errno ();
            echo 'ERROR MESSAGE: ' . $channel->errmsg ();
            echo 'REQUEST ID: ' . $channel->getRequestId ();
        }
        else
        {
            echo 'SUCC, ' . __FUNCTION__ . ' OK!!!!!';
            echo 'result: ' . print_r($ret1, true);
        }
    
        if (false === $ret2) {
            echo 'WRONG, ' . __FUNCTION__ . ' ERROR!!!!!';
            echo 'ERROR NUMBER: ' . $channel->errno ();
            echo 'ERROR MESSAGE: ' . $channel->errmsg ();
            echo 'REQUEST ID: ' . $channel->getRequestId ();
        }
        else
        {
            echo 'SUCC, ' . __FUNCTION__ . ' OK!!!!!';
            echo 'result: ' . print_r($ret2, true);
        }
    }
}

