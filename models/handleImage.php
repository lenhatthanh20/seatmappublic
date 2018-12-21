<?php
CONST _IMAGE_SIZE_2MB = 2000000;

require_once('../models/Utility.php');
require_once('../libs/custom/handle/constantMessage.php');
require_once('../models/basicValidation.php');

class HandleImage {

    private $error;
    /**
     * This variable is described for image type of object
     * @var null
     */
    private $imageType = null;

    /**
     * This variable is described for image size of object
     * @var null
     */
    private $imageSize = null;

    /**
     * This variable is described for image path of object
     * @var null
     */
    private $imagePath = null;

    /**
     * This variable is described for error handling
     * @var array
     */
    //private $error = [];
    private $fileInputName;

    private $minFileSize = null;
    private $maxFileSize = null;
    private $allowExtension = [];

    /**
     * Call this method to get singleton of Utility Object
     * @return Utility
     */
    public static function Instance()
    {
        static $utility = null;
        if ($utility === null) {
            $utility = new Utility();
        }
        return $utility;
    }

    /**
     * Set image type
     * @param $type
     */
    public function setImageType($type){
        $this->imageType = $type;
    }

    /**
     * Set image size
     * @param $size
     */
    public function setImageSize($size){
        $this->imageSize = $size;
    }

    /**
     * Set image path
     * @param $path
     */
    public function setImagePath($path){
        $this->imagePath = $path;
    }

    /**
     * Set error when handle image
     * @param $errorMessage
     */
    public function setImageError($errorMessage){
        array_push($this->error,  $errorMessage);
    }

    /**
     * Get image type
     * @return null
     */
    public function getImageType(){
        return $this->imageType;
    }

    /**
     * Get image size
     * @return null
     */
    public function getImageSize(){
        return $this->imageSize;
    }

    /**
     * Get image path
     * @return null
     */
    public function getImagePath(){
        return $this->imagePath;
    }

    /**
     * Get all errors when handle image
     * @return array
     */
    public function getImageError(){
        return $this->error;
    }

    /**
     * @param $uploadFile
     */
    public function setImageParams($uploadFile) {
        /* Set image path */
        $this->setImagePath(_IMAGE_SEAT_MAP_DIR . (HandleImage::Instance())->cleanSpecialChars(basename($uploadFile["name"])));
        /* Set image type */
        $this->setImageType(strtolower(pathinfo($this->getImagePath(), PATHINFO_EXTENSION)));
        /* Set image size */
        $this->setImageSize($uploadFile["size"]);
    }

    /**
     * Check uploaded file is a image or not
     * @param $uploadFile
     * @return bool
     */
    public function isImage($uploadFile) {
        $check = getimagesize($uploadFile['tmp_name']);
        if ($check !== false) {
            return true;
        }
        return false;
    }

    /**
     * Rename image file when that name is exist in database
     * @param $uploadFile
     */
    public function renameFileWhenExist($uploadFile) {
        if (file_exists($this->getImagePath())) {
            $temp = explode('.', $uploadFile['name']);
            $this->setImagePath('../images/seatMap/' . round(microtime(true)) . '.' . end($temp));
        }
    }

    /**
     * Validation the image size is greater than the specific size of not.
     * @param int $size
     * @return bool
     */
    public function isLarge($size = _IMAGE_SIZE_2MB) {
        if ($this->getImageSize() > $size) {
            return true;
        }
        return false;
    }

    /**
     * Validation allowing certain file formats: JPG, PNG, JPEG, GIF
     * @return bool
     */
    public function allowCertainFormat() {
        if ($this->getImageType() != "jpg" && $this->getImageType() != "png" && $this->getImageType() != "jpeg"
            && $this->getImageType() != "gif") {
            return true;
        }
        return false;
    }

    /**
     * Validation image is upload or not
     * @param $uploadFile
     * @return bool
     */
    public function isUploadImage($uploadFile) {
        if(is_uploaded_file($uploadFile['tmp_name'])) {
            return false;
        }
        return true;
    }

    public function removeOldFileAndMoveNewFile($fileUpload, $oldFile, $newFile) {
        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
        move_uploaded_file($fileUpload['tmp_name'], $newFile);
    }

    /**
     * Validation full image
     * @param $uploadFile
     */
    public function imageValidationFull($uploadFile) {

        if (!$this->isImage($uploadFile)) {
            $this->setImageError(_NOT_IMAGE);
        }

        if ($this->isLarge(_IMAGE_SIZE_10MB)) {
            $this->setImageError(_FILE_IS_LARGE);
        }

        if ($this->allowCertainFormat()) {
            $this->setImageError(_TYPE_NOT_ALLOW);
        }
    }

    public function imageHandleFull($uploadFile) {
        /* Set parameter */
        $this->setImageParams($uploadFile);

        /* Rename image when file name is exist */
        $this->renameFileWhenExist($uploadFile);

        /* Validation image */
        $this->imageValidationFull($uploadFile);

    }

    /**
     * Move file to a specific location
     * @param $uploadFile
     */
    public function moveFileUploaded($uploadFile) {
        move_uploaded_file($uploadFile["tmp_name"], $this->getImagePath());
    }

    /**
     * Handle uploading image. This is a main method to call in controller
     * @param $fileName
     */
    public function uploadImage($fileName) {
        /* Image is uploaded */
        if (!$this->isUploadImage($fileName)) {

            /* Handle, validation image */
            $this->imageHandleFull($fileName);

        } else {

            /* Image is not uploaded */
            $this->setImageError(_NOT_UPLOAD);
        }
    }

    public function uploadImageExceptOldImage($fileName) {
        /* Image is uploaded */
        if (!$this->isUploadImage($fileName)) {

            /* Handle, validation image */
            $this->imageHandleFull($fileName);

            return true;
        }
        return false;
    }


}