<?php
define('REST_DEBUG', true);

class RestModule extends CWebModule
{
	public function init()
	{
		// import the module-level models and components
		$this->setImport(array(
			'rest.models.*',
			'rest.components.*',
			'rest.extensions.*',
		));
		
		app()->errorHandler->errorAction = 'rest/default/error';
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action)) {
			return true;
		}
		else
			return false;
	}
    
}


if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }
}


