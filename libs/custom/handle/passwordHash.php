<?php

class PasswordHash {
    public function hashPassword($password) {
        $passwordString = password_hash($password, PASSWORD_BCRYPT );
        return $passwordString;
    }

    public function comparePassword($password, $hashString) {
        $result = password_verify($password, $hashString);
        if($result) {
            return true;
        } else {
            return false;
        }
    }
}