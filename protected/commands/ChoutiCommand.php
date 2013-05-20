<?php
class ChoutiCommand extends  CConsoleCommand
{
    public function actionLogin()
    {
        $id = 4854955; // 4854960
        $names = self::usernames();
        foreach ($names as $name) {
            self::userLogin($name);
            sleep(5);
        }
        
        foreach ($names as $name) {
            self::digPost($id, $name);
            sleep(5);
        }
    }
    
    private static function userLogin($name)
    {
        $data = array(
            'jid' => $name,
            'password' => self::password($name),
            'autologin' => 1,
        );
        
        $file = app()->getRuntimePath() . DS . $name . '.txt';
        $curl = new CDCurl();
        $curl->cookiefile($file);
        $curl->post('http://dig.chouti.com/login', $data);
        if ($curl->errno() == 0) {
            $result = $curl->rawdata();
            echo $name . ' - ';
            print_r($result);
        }
        else
            echo $curl->error();
        
        $curl->close();
        $curl = null;
        
        echo "\n---------------login complete----------------------------\n";
    }
    
    private static function digPost($id, $name)
    {
        $url = 'http://dig.chouti.com/link/vote?linksId=' . (int)$id;
        $file = app()->getRuntimePath() . DS . $name . '.txt';
        $curl = new CDCurl();
        $curl->cookiefile($file);
        $headers = array(
            'X-Requested-With:XMLHttpRequest',
        );
        $curl->headers($headers);
        $curl->post($url);
        if ($curl->errno() == 0) {
            $result = $curl->rawdata();
            echo $id . ' - ' . $name . ' - ';
            print_r($result);
        }
        else
            echo $curl->error();
        
        $curl->close();
        $curl = null;
        
        echo "\n-------------------dig complete------------------------\n";
    }
    
    
    public function actionRegister()
    {
        $names = self::names();
        foreach ($names as $name) {
            self::newUser($name);
            sleep(8);
        }
    }
    
    private static function newUser($name)
    {
        $data = array(
            'jid' => $name,
            'password' => self::password($name),
            'email' => self::email($name),
            'readPl' => 1,
        );
        
        $curl = new CDCurl();
        $curl->post('http://dig.chouti.com/register', $data);
        if ($curl->errno() == 0) {
            $result = $curl->rawdata();
            echo $name . ' - ';
            print_r($result);
        }
        else
            echo $curl->error();
        
        $curl->close();
        $curl = null;
        
        echo "\n-------------register complete------------------------------\n";
    }
    
    public static function names()
    {
        return array(
            
        );
    }
    
    public static function usernames()
    {
        return array(
//             'seaman5126',
//             'wangbo0712',
//             'jiafei8817',
//             'shnegongsi',
//             'fay6111',
//             'jiexiaboy',
//             'pabubu',
//             'yaoyucai',
//             'xinyongshang',
//             'jnkchf',
//             'lightegg',
//             'zhaosh27',
//             'wutong21',
//             'papaqinqin',
//             'miker',
//             'cxserve',
//             'shunjinglove',
//             'hacksundy',
//             'sly0715',
            'langyu510',
            'lz11421',
            'jinue',
//             'lxc18d',
//             'liuyuyue3775',
//             'ljysucc',
//             'loggerhead',
        );
    }
    
    public static function password($name)
    {
        return $name . '2013';
    }
    
    public static function email($name)
    {
        $domains = array(
            '126.com',
            '163.com',
            'yahoo.com',
            'aliyun.com',
            'sina.com.cn',
            'sohu.com',
            'qq.com',
            'gmail.com',
            'office.com',
        );
        $index = mt_rand(0, count($domains)-1);
        return $name . '@' . $domains[$index];
    }
}


