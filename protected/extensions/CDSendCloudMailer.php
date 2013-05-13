<?php
class CDSendCloudMailer extends CApplicationComponent
{
    public $username;
    public $password;
    public $replyTo;
    public $fromName;
    public $fromAddress;
    
    /**
     * @var SendCloud
     */
    private $_sender;
    private $_message;
    
    public function init()
    {
        if (empty($this->username) || empty($this->password))
            throw new Exception('username or password is required');
        
        $this->initSendCloud();
    }
    
    private function initSendCloud()
    {
        $this->setClassMap();
        $this->_sender = new SendCloud($this->username, $this->password);
    }
    
    /**
     * 实例化SendCloud\Message实例
     * @return SendCloud\Message
     */
    public function message()
    {
        $this->_message = new SendCloud\Message();
        if ($this->replyTo)
            $this->_message->setReplyTo($this->replyTo);
        if ($this->fromName)
            $this->_message->setFromName($this->fromName);
        if ($this->fromAddress)
            $this->_message->setFromAddress($this->fromAddress);
        return $this->_message;
    }
    
    public function send($message = null)
    {
        if ($message)
            return $this->_sender->send($message);
        elseif ($this->_message)
            return $this->_sender->send($this->_message);
        else
            throw new Exception('message is invalid');
    }
    
    public function sendSimple($email, $subject, $body)
    {
        $this->message()
            ->addRecipient($email)
            ->setSubject($subject)
            ->setBody($body);
        return $this->send();
    }
    
    private function setClassMap()
    {
        $basePath = Yii::getPathOfAlias('application.libs.sendcloud') . DIRECTORY_SEPARATOR;
        $classMap = array(
            'SendCloud' => $basePath . 'SendCloud.php',
            'PHPMailer' => $basePath . 'lib/phpmailer/class.phpmailer.php',
            'SMTP' => $basePath . 'lib/phpmailer/class.smtp.php',
            'SendCloud\Smtp' => $basePath . 'SendCloud/Smtp.php',
            'SendCloud\Message' => $basePath . 'SendCloud/Message.php',
            'SendCloud\AppFilter' => $basePath . 'SendCloud/AppFilter.php',
            'SendCloud\SmtpApiHeader' => $basePath . 'SendCloud/SmtpApiHeader.php',
        );
        Yii::$classMap = array_merge(Yii::$classMap, $classMap);
    }
    
}