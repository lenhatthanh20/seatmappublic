<?php

require_once('../models/Utility.php');
require_once('../libs/custom/handle/constantMessage.php');

define ('CAN_NOT_UPLOAD_FILE', 9);

class uploadImage
{

    private $error;

    private $imageType;

    private $imageSize;

    private $imagePath;

    private $fileInputName;

    private $maxFileSize;

    private $allowExtension = [];

    private $isRequired = false;

    public static function Instance()
    {
        static $utility = null;
        if ($utility === null) {
            $utility = new Utility();
        }
        return $utility;
    }

    public function __construct($fileInputName = '')
    {
        $this->fileInputName = $fileInputName;
        $file = $_FILES[$fileInputName];
        $this->imagePath = _IMAGE_SEAT_MAP_DIR . (uploadImage::Instance())->cleanSpecialChars(basename($file["name"]));
        $this->imageType = strtolower(pathinfo($this->getImagePath(), PATHINFO_EXTENSION));
        $this->imageSize = $file["size"];
        $this->error = $file['error'];
    }

    public function setImageType($type){
        $this->imageType = $type;
    }

    public function setImageSize($size){
        $this->imageSize = $size;
    }

    public function setImagePath($path){
        $this->imagePath = $path;
    }

    public function setAllowExtension($arrayExt) {
        $this->allowExtension = $arrayExt;
    }

    public function getImageType(){
        return $this->imageType;
    }

    public function setMaxFileSize($maxSize) {
        $this->maxFileSize = 1000 * $maxSize;
    }

    public function setIsRequired() {
        $this->isRequired = true;
    }

    public function getImageSize(){
        return $this->imageSize;
    }

    public function getImagePath(){
        return $this->imagePath;
    }

    public function getInputFileName() {
        return $this->fileInputName;
    }

    public function getMaxFileSize() {
        return $this->maxFileSize;
    }

    public function getAllowExtension(){
        return $this->allowExtension;
    }

    public function getIsRequired(){
        return $this->isRequired;
    }

    public function getError(){
        return $this->error;
    }

    public function getErrorDescription(){
        $message = false;
        switch ($this->error){
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_file size directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                if($this->getIsRequired()){
                    $message = "No file was uploaded";
                }
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;
            /* User ERROR */
            case CAN_NOT_UPLOAD_FILE:
                $message = "Can not upload file";
                break;
            case _FILE_IS_LARGE:
                $message = _FILE_IS_LARGE;
                break;
            case _TYPE_NOT_ALLOW:
                $message = _TYPE_NOT_ALLOW;
                break;
            case _NOT_IMAGE:
                $message = _NOT_IMAGE;
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }

    public function upload(){
        if(!$this->getError()){
            $file = $_FILES[$this->getInputFileName()];
            $this->renameFileWhenExist($file);
            if(move_uploaded_file($file["tmp_name"], $this->getImagePath())){
                return true;
            } else {
                $this->error = CAN_NOT_UPLOAD_FILE;
            }
        }
        return false;
    }

    public function userValidation(){
        if(!$this->getError()) {
            if ($this->getImageSize() > $this->getMaxFileSize()) {
                $this->error = _FILE_IS_LARGE;
            }

            if(!in_array($this->getImageType(), $this->getAllowExtension())){
                $this->error = _TYPE_NOT_ALLOW;
            }

            $check = getimagesize($_FILES[$this->getInputFileName()]['tmp_name']);
            if ($check === false) {
                $this->error = _NOT_IMAGE;
            }
        }
    }

    public function renameFileWhenExist($file) {
        if (file_exists($this->getImagePath())) {
            $temp = explode('.', $file['name']);
            $this->setImagePath('../images/seatMap/' . round(microtime(true)) . '.' . end($temp));
        }
    }

    public function removeOldFileAndMoveNewFile($oldFile, $newFile) {
        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
        move_uploaded_file($_FILES[$this->getInputFileName()]['tmp_name'], $newFile);
    }

    public function isUploadImage() {
        if(is_uploaded_file($_FILES[$this->getInputFileName()]['tmp_name'])) {
            return true;
        }
        return false;
    }
}