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
        $optional[Channel::USER_ID] = '1032337158820187361';
        $optional[Channel::DEVICE_TYPE] = BAIDU_DEVICE_TYPE_IOS;
        $optional[Channel::MESSAGE_TYPE] = BAIDU_MESSAGE_TYPE_ALERT;
        $message = array(
            'aps' => array(
                'alert' => 'Message From Baidu Push',
                'Sound' => '',
                'Badge' => 0
            ),
        );
        
        $ret = $channel->pushMessage (BAIDU_PUSH_TYPE_SINGLE, $message, 'test_key', $optional);
        
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
}

