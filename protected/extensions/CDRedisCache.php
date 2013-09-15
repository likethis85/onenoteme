<?php
class CDRedisCache extends CCache
{
    /**
     * redis server host
     * @var string
     */
    public $host;
    
    /**
     * redis server port，default 6379
     * @var integer
     */
    public $port = 6379;
    
    /**
     * redis default db index
     * @var integer
     */
    public $dbindex = 0;
    
    /**
     * redis server timeout，default 0
     * @var float
     */
    public $timeout = 0;
    
    /**
     * create one persistent connection, default false
     * @var bool
     */
    public $persistent = false;
    
    /**
     * redis server password if required auth
     * @var string
     */
    public $password;
    
    /**
     * redis server options
     * @var array
     */
    public $options;
    
    
    /**
     * redis instance
     * @var Redis
     */
    private $_client;
    
    
    public function init()
    {
        if (empty($this->host) || filter_var($this->host, FILTER_VALIDATE_IP) === false)
            throw new RedisException('host is invalid.');
        
        parent::init();
        $this->serializer = false; // please set Redis::OPT_SERIALIZER option
        $this->keyPrefix = null; // please set Redis::OPT_PREFIX option
    }
    
    protected function connect()
    {
        $this->_client = new Redis();
        $result = $this->persistent
            ? $this->_client->pconnect($this->host, $this->port, $this->timeout)
            : $this->_client->connect($this->host, $this->port, $this->timeout);
        
        if ($result === false)
            throw new RedisException($this->_client->getLastError());
        
        if ($this->password) {
            $result = $this->_client->auth($this->password);
            if (!$result)
                throw new RedisException($this->_client->getLastError());
        }
        
        if ($this->options)
            $this->setOptions($this->options);
        
        $this->selectDb($this->dbindex);
    }
    
    public function executeCommand($name, $params = array())
    {
        if ($this->_client === null)
            $this->connect();
        
        if (method_exists($this->_client, $name)) {
            return call_user_func_array(array($this->_client, $name), $params);
        }
        else
            throw new CException('redis server is not connected or command is invalid.');
        
    }
    
    public function setValue($key, $value, $expire, $milliseconds = false)
    {
        if ($expire > 0) {
            return $milliseconds ? $this->executeCommand('psetex', array($key, $expire, $value)) : $this->executeCommand('setex', array($key, $expire, $value));
        }
        else
            return $this->executeCommand('set', array($key, $value));
    }
    
    public function addValue($key, $value, $expire, $milliseconds = false)
    {
        if ($this->executeCommand('setnx', array($key, $value))) {
            if ($expire > 0) {
                $result = $milliseconds ? $this->executeCommand('pexpire', array($key, $expire)) : $this->executeCommand('expire', array($key, $expire));
                if (!$result && !$this->executeCommand('del', array($key)))
                    throw new RedisException('setnx success, but expire failed');
            }
            return true;
        }
        return false;
    }
    
    public function getValue($key)
    {
        return $this->executeCommand('get', array($key));
    }
    
    public function getValues($keys)
    {
        return $this->executeCommand('mget', array($keys));
    }
    
    public function deleteValue($key)
    {
        return $this->executeCommand('del', array($key));
    }
    
    public function flushValues($alldb = false)
    {
        return $alldb ? $this->executeCommand('flushAll') : $this->executeCommand('flushDb');
    }
    
    public function client()
    {
        return $this->_client;
    }
    
    public function selectDb($index)
    {
        return $this->executeCommand('select', array($index));
    }
    
    public function setOptions(array $options)
    {
        $result = true;
        foreach ($options as $name => $value)
            $result = $result && $this->setOption($name, $value);
        
        return $result;
    }
    
    public function setOption($name, $value)
    {
        return $this->executeCommand('setOption', array($name, $value));
    }
    
    public function getOption($name)
    {
        return $this->executeCommand('getOption', array($name));
    }
    
    public function close()
    {
        $this->executeCommand('IsConnected') && $this->executeCommand('close');
    }
    
    public function __call($name, $params)
    {
        $this->executeCommand($name, $params);
        
        parent::__call($name, $params);
    }
    
    protected function generateUniqueKey($key)
    {
        return $key;
    }
}




