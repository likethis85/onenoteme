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
        $tempName = $upload->getTempName();
        if ($upload === null || $upload->error != UPLOAD_ERR_OK || !file_exists($tempName) || !is_readable($tempName))
            return false;
        
        $upyunEnabled = (bool)param('upyun_enabled');
        if ($upyunEnabled)
            $result = $this->uploadAvatarToUpyun();
        else
            $result = $this->uploadAvatarToLocal();
        
        return $result;
    }
    
    public function uploadAvatarToUpyun()
    {
        $tempName = $this->avatar_large->getTempName();
        $extension = CDImage::getImageExtName($tempName);
        $path = CDBase::makeUploadPath('avatars');
        $urlpath = '/' . trim($path['url'], '/') . '/';
        $file = CDBase::makeUploadFileName($extension);
        $filename = $urlpath . $this->user_id . '_' . $file;
    
        try {
            $uploader = app()->getComponent('upyunimg');
            $uploader->setFilename($filename);
            $fileData = file_get_contents($tempName);
            $uploader->upload($fileData);
            $this->original_avatar = $uploader->getFileUrl();
            $this->avatar_large = $this->getLargeAvatarUrl();
            $this->image_url = $this->getSmallAvatarUrl();
            return true;
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        
        return false;
    }
    
    public function uploadAvatarToLocal()
    {
        $upload = $this->avatar_large;
    
        $tempName = $upload->avatar_large->getTempName();
        $extension = CDImage::getImageExtName($tempName);
        $path = CDBase::makeUploadPath('avatars');
        $file = CDBase::makeUploadFileName($extension);
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