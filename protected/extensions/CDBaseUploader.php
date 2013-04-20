<?php
abstract class CDBaseUploader extends CApplicationComponent
{
    abstract public function autoFilename($prefixPath = '', $extension = '', $prefix = '');
    
    abstract public function save($file);
}