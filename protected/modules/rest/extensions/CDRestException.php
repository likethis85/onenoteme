<?php
class CDRestException extends CException
{
    public $appcode;
    public function __construct($code = null, $message = null, $previous = null)
    {
        $this->appcode = $code;
        
        $messages[] = CDRestError::messageByCode($code);
        $message && $messages[] = '; ' . $message;
        $messages = array_filter($messages);
        $message = join(', ', $messages);
        
        parent::__construct($message, $code, $previous);
    }
}