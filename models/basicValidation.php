<?php

class basicValidation {

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

}