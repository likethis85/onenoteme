<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 14-1-3
 * Time: 下午5:09
 */

$connect = new Memcached;
$connect->setOption(Memcached::OPT_COMPRESSION, false);
$connect->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
$connect->addServer('131ce938744011e3.m.cnhzalicm10pub001.ocs.aliyuncs.com', 11211);
$connect->setSaslAuthData('131ce938744011e3', 'cdc_123123');
$r = $connect->set("hello", "world", time()+30);
var_dump($r);
$r = $connect->get("hello");
var_dump($r);
$connect->quit();