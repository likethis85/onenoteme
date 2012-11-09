<?php
class WdzWeixin extends CDWeixin
{
    public function processRequest($data)
    {
        $text = 'hello wordl!!!!';
        echo $this->outputText($data->ToUserName, $data->FromUserName, $text);
    }
}