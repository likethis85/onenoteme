<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 14-1-3
 * Time: 下午3:23
 *
 */

class CDMemCache extends CMemCache
{
    public $username;
    public $password;

    /**
     * @var array memcached extension options
     */
    public $options;

    /**
     * @throws CException if extension isn't loaded
     * @return Memcache|Memcached the memcache instance (or memcached if {@link useMemcached} is true) used by this component.
     */
    public function getMemCache()
    {
        $cache = parent::getMemCache();

        if ($this->useMemcached && $this->options && is_array($this->options)) {
            $cache->setOptions($this->options);
        }

        if ($this->useMemcached && $this->username && $this->password)
            $cache->setSaslAuthData($this->username, $this->password);

        return $cache;
    }
}
