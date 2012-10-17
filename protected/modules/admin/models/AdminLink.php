<?php
/**
 * AdminLink
 * @author chendong
 */
class AdminLink extends Link
{
    /**
     * Returns the static model of the specified AR class.
     * @return AdminLink the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 以后备用，现在友链的图全是url，以后可以直接上传图片
     */
    public function saveIcon()
    {
        return ;
        if ($this->icon && $this->icon instanceof CUploadedFile) {
            $topicThumbnailDir = 'topic';
            $filename = BetaBase::uploadImage($this->icon, 'topic');
            if ($filename === false)
                return false;
            else {
                $this->icon = $filename['url'];
                $this->update(array('icon'));
                return $filename;
            }
        }
        else
            return null;
    }
}