<?php
CONST _IMAGE_SIZE_2MB = 2000000;

class ImageValidation {

    /**
     * Check uploaded file is a image or not
     * @param $file
     * @return bool
     */
    public function isImage($file) {
        $check = getimagesize($file);
        if ($check !== false) {
            return true;
        }
        return false;
    }

    /**
     * Rename image file when that name is exist in database
     * @param $file
     * @return string
     */
    public function renameFileWhenExist($file) {
        $temp = explode(".", $file);
        return "../images/seatmap/" . round(microtime(true)) . '.' . end($temp);
    }

    /**
     * Check the image size is greater than the specific size of not.
     * @param $fileSize
     * @param int $size
     * @return bool
     */
    public function isLarge($fileSize, $size = _IMAGE_SIZE_2MB) {
        if ($fileSize > $size) {
            return true;
        }
        return false;
    }

    /**
     * Allow certain file formats: JPG, PNG, JPEG, GIF
     * @param $fileType
     * @return bool
     */
    public function allowCertainFormat($fileType) {
        if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
            && $fileType != "gif") {
            return true;
        }
        return false;
    }

    /**
     * Validation image
     * @param $file
     * @param $fileType
     * @return array
     */
    public function imageValidationFull($file, $fileType) {
        $error = [];
        if (!$this->isImage($file['tmp_name'])) {
            array_push($error,  _NOT_IMAGE);
        }

        if ($this->isLarge($file["size"], _IMAGE_SIZE_10MB)) {
            array_push($error,  _FILE_IS_LARGE);
        }

        // Allow certain file formats
        if ($this->allowCertainFormat($fileType)) {
            array_push($error, _TYPE_NOT_ALLOW);
        }
        return $error;
    }
}