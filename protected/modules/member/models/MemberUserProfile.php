<?php
class MemberUserProfile extends UserProfile
{
    /**
     * Returns the static model of the specified AR class.
     * @return MemberUserProfile the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function uploadAvatar()
    {
        $upload = $this->avatar_large;
        if ($this->avatar_large === null || $this->avatar_large->error != UPLOAD_ERR_OK)
            return false;
        
        $path = CDBase::makeUploadPath('avatars');
        $file = CDBase::makeUploadFileName('');
        $largeFile = $path['path'] . 'large_' . $file;
        $smallFile = $path['path'] . 'small_' . $file;
        
        $data = file_get_contents($upload->getTempName());
        $im = new CDImage($data);
        unset($data);
        
        $largeSize = param('large_avatar_size');
        $im->resize($largeSize, $largeSize);
        $im->saveAsJpeg($largeFile);
        $this->avatar_large = $path['url'] . $im->filename();
        
        $smallSize = param('small_avatar_size');
        $im->resize($smallSize, $smallSize);
        $im->saveAsJpeg($smallFile);
        $this->image_url = $path['url'] . $im->filename();
        
        return true;
    }
}