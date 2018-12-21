<?php
require_once('../models/SeatMap.php');
require_once('../libs/custom/handle/constantMessage.php');

class basicValidation {

    private $error;

    /**
     * Call this method to get singleton of Utility Object
     * @return SeatMap
     */
    public static function Instance()
    {
        static $seatMap = null;
        if ($seatMap === null) {
            $seatMap = new SeatMap();
        }
        return $seatMap;
    }

    public function setError($errorMessage){
        $this->error = $errorMessage;
    }

    public function getError(){
        return $this->error;
    }

    public function lengthOfName($name, $minLength, $maxLength) {
        $len = strlen($name);
        if($len < $minLength){
            return false;
        }
        elseif($len > $maxLength){
            return false;
        }
        return true;
    }

    public function validationName($name) {

        if(empty($name)) {
            $this->setError(_SEAT_MAP_NAME_REQUIRED);
            return false;
        }
        if ((basicValidation::Instance())->checkExistName($name)) {
            $this->setError(_SEAT_MAP_NAME_EXIST);
            return false;
        }
        if(!$this->lengthOfName($name, _MIN_LENGTH, _MAX_LENGTH)) {
            $this->setError(_LENGTH_INVALID);
            return false;
        }
        return true;
    }

    public function validationId ($id) {
        /* Validation empty username */
        if(empty($id)){
            $this->setError(_ID_REQUIRED);
            return false;
        }

        /* Check ID is exist or not */
        if(!(basicValidation::Instance())->checkValidId($id)) {
            $this->setError(_ID_NOT_EXIST);
            return false;
        }
        return true;
    }

    public function validationNameExceptId($name, $id) {

        if(empty($name)) {
            $this->setError(_SEAT_MAP_NAME_REQUIRED);
            return false;
        }

        if ((basicValidation::Instance())->checkExistNameExceptId($name, $id)) {
            $this->setError(_SEAT_MAP_NAME_EXIST);
            return false;
        }

        if(!$this->lengthOfName($name, _MIN_LENGTH, _MAX_LENGTH)) {
            $this->setError(_LENGTH_INVALID);
            return false;
        }
    }


}