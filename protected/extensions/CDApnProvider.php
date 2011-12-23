<?php

class CDApnProvider extends CApplicationComponent
{
    const APN_PORT = 2195;
    const APN_SANDBOX_HOST = 'gateway.sandbox.push.apple.com';
    const APN_HOST = 'gateway.push.apple.com';
    
    public $sandbox = false;
    public $port = 2195;
    public $cert;
    public $pass = '';
    public $timeout = 60;
    
    
    private $_host;
    private $_connection;
    private $_note;
    
    public function __construct()
    {
        
    }
    
    public function init()
    {
        parent::init();
        
        $this->_host = $this->sandbox ? self::APN_SANDBOX_HOST : self::APN_HOST;
        
        if (empty($this->cert))
            throw new CException('cert not found', 0);
        
    }
    
    public function send()
    {
        if ($this->_note instanceof CDApnNote)
            $msg = $this->_note->package();
        else
            $msg = trim($this->_note);
        
        $result = fwrite($this->_connection, $msg);
        
        echo "msg: $msg\n";
        
        if ($result === false)
            echo "Send Error\n";
        else
            echo "Send Success\n";
        
        return $this;
    }
    
    public function connect()
    {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->cert);
        // assume the private key passphase was removed.
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->pass);
        $host = 'ssl://' . $this->_host . ':' . $this->port;
        echo "host: $host\n";
        $this->_connection = stream_socket_client($host, $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT, $ctx);
        
        if ($this->_connection) {
            echo "Connection OK\n";
        }
        else {
            echo "Failed to connect $errno, $errstr\n";
        }
        
        return $this;
    }
    
    public function close()
    {
        fclose($this->_connection);
    }

    public function createNote($deviceToken, $message, $badge = 0, $sound = '', array $others = null)
    {
        $this->_note = new CDApnNote($deviceToken, $message, $badge, $sound, $others);
        return $this;
    }
}

class CDApnNote
{
    public $deviceToken;
    public $badge;
    public $message;
    public $sound;
    public $others;
    
    
    public function __construct($deviceToken, $message, $badge = 0, $sound = '', array $others = null)
    {
        $this->deviceToken = $deviceToken;
        $this->message = trim($message);
        $this->badge = (int)$badge;
        $this->sound = trim($this->sound);
        $this->others = $others;
    }
    
    private function payload()
    {
        $aps['alert'] = $this->message;
        if ($this->badge > 0)
            $aps['badge'] = $this->badge;
        if ($this->sound)
            $aps['sound'] = $this->sound;
        
        $body = array('aps'=>$aps);
        if ($this->others)
            $body = array_merge($body, $this->others);
        return $body;
    }
    
    public function package()
    {
        $payload = json_encode($this->payload());
        $msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $this->deviceToken)) . pack("n",strlen($payload)) . $payload;
        return $msg;
    }
}

