<?php
class CDApiException extends Exception
{
    public function __construct($code = null, $message = null, $previous = null)
    {
        $messages[] = ApiError::messageByCode($code);
        $messages[] = $message;
        $messages = array_filter($messages);
        $message = join(', ', $messages);
        
        parent::__construct($message, $code, $previous);
    }
}