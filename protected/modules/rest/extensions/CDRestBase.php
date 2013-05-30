<?php
class CDRestBase
{
    /**
     * 返回json编码数据
     * @param mixed $data
     * @return string json编码后的数据
     */
    public static function outputJson($data)
    {
        return CJSON::encode($data);
    }
}


