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
     * redis instance
     * @var Redis
     */
    private $_client;
    
    
    
    public function init()
    {
        if (empty($this->host) || filter_var($this->host, FILTER_VALIDATE_IP) === false)
            throw new RedisException('host is invalid.');
        
        parent::init();
        
        $this->_client = new Redis();
        if ($this->persistent)
            $result = $this->_client->pconnect($this->host, $this->port, $this->timeout);
        else
            $result = $this->_client->connect($this->host, $this->port, $this->timeout);
        
        if (!$result)
            throw new RedisException($this->_client->getLastError());
        
        if ($this->password)
            $this->_client->auth($this->password);
    }
    
    public function setValue($key, $value, $expire, $milliseconds = false)
    {
        if ($expire > 0) {
            return $milliseconds ? $this->_client->psetex($key, $expire, $value) : $this->_client->setex($key, $expire, $value);
        }
        else
            return $this->_client->set($key, $value);
    }
    
    public function addValue($key, $value, $expire, $milliseconds = false)
    {
        if ($this->_client->setnx($key, $value)) {
            if ($expire > 0) {
                $result = $milliseconds ? $this->_client->pexpire($key, $expire) : $this->_client->expire($key, $expire);
                if (!$result && !$this->_client->del($key))
                    throw new RedisException('setnx success, but expire failed');
            }
            return true;
        }
        return false;
    }
    
    public function getValue($key)
    {
        return $this->client()->get($key);
    }
    
    public function getValues($keys)
    {
        return $this->client()->mget($keys);
    }
    
    public function deleteValue($key)
    {
        return $this->_client->del($key);
    }
    
    public function flushValues($alldb = false)
    {
        return $alldb ? $this->_client->flushAll() : $this->_client->flushDB();
    }
    
    
    public function client()
    {
        return $this->_client;
    }
    
    public function selectDb($index)
    {
        $this->_client->select($index);
    }
}




