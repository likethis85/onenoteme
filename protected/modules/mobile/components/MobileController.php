<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class MobileController extends CController
{
    public $channel;
    public $clientID = null;
    public $lastVisit = array();
    

    public function init()
    {
        parent::init();
         
        $this->lastVisit = CDBase::getClientLastVisit();
         
        $this->clientID = CDBase::getClientID();
        if (empty($this->clientID))
            $this->clientID = CDBase::setClientID();
    }
    
    public function actions()
    {
        return array(
            'captcha'=>array(
                'class'=>'application.extensions.CDCaptchaAction.CDCaptchaAction',
                'backColor' => 0xFFFFFF,
                'height' => 22,
                'width' => 70,
                'maxLength' => 4,
                'minLength' => 4,
                'foreColor' => 0xFF0000,
                'padding' => 3,
                'testLimit' => 3,
            ),
        );
    }
    
    public function getHomeUrl()
    {
        return aurl('mobile/default/index');
    }
    

    public function setKeywords($content)
    {
        cs()->registerMetaTag($content, 'keywords');
    }
    
    public function setDescription($content)
    {
        cs()->registerMetaTag($content, 'description');
    }
    
	public function setSiteTitle($value)
	{
        $titles = array(param('sitename'));
        if (param('shortdesc'))
            array_push($titles, param('shortdesc'));
        if (!empty($value))
    	    array_unshift($titles, $value);

        $text = strip_tags(trim(join(' - ', $titles)));
	    $this->pageTitle = $text;
	}

	protected function beforeRender($view)
	{
	    cs()->defaultScriptFilePosition = CClientScript::POS_END;
	    return true;
	}
}