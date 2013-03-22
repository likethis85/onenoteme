<?php
abstract class CDUploader extends CApplicationComponent
{
    abstract public function autoFilename($prefixPath = '', $extension = '', $prefix = '');
    
    abstract public function upload($file);
}