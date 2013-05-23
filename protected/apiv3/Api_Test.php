<?php
class Api_Test extends ApiBase
{
    public function abc()
    {
        $this->requiredParams(array('username', 'password'));
        $params = $this->filterParams(array('username', 'password'));
        
        var_dump($params);
    }
}