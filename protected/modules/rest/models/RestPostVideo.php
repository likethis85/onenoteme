<?php
class RestPostVideo extends PostVideo
{
    /**
     * Returns the static model of the specified AR class.
     * @return RestPostVideo the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function getApiSourceUrl()
    {
        try {
            $vk = new CDVideoKit();
    	    $vk->setAppKeysMap(CDBase::videoAppKeysMap());
    	    $vk->setVideoUrl($this->source_url);
    	    return $vk->getMobileSourceUrl();
        }
        catch (Exception $e) {
            return false;
        }
    }
}