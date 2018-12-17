<?php
require_once ('databaseConfig.php');

class User {

    /**
     * Call this method to get singleton
     * @return Database
     */
    public static function Instance()
    {
        static $database = null;
        if ($database === null) {
            $database = new Database();
        }
        return $database;
    }

    /**
     * @method: Add user
     * @param: $username, $password
     * @return: bool. Return true if add user successfully, false if failure.
     */
    public function addUser($username, $password) {
        $username = mysqli_real_escape_string((User::Instance())->connect(), $username);
        $sql = "INSERT INTO User (username, password) VALUES ('{$username}', '{$password}')";
        $result = (User::Instance())->databaseQuery($sql);
        if(isset($result)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @method: Check username is exist or not
     * @param: $username
     * @return: bool. Return true if username is not exist, false if username is exist.
     */
    public function checkExistUsername($username) {
        $username = mysqli_real_escape_string((User::Instance())->connect(), $username);
        $sql = "SELECT IF( EXISTS(
                            SELECT username
                            FROM user
                            WHERE username = '{$username}'
                            LIMIT 1), 'exist', 'notExist')";
        $result = (User::Instance())->databaseQuery($sql);
        $result = (User::Instance())->databaseFetch($result);
        if($result[0][0] === 'notExist') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @method: Login Handle
     * @param: $username
     * @return: object|bool. Return object if username is selected, false if username is not exist.
     */
    public function loginHandle($username){
        $username = mysqli_real_escape_string((User::Instance())->connect(), $username);
        $sql = "SELECT * FROM user WHERE username = '{$username}'";
        $result = (User::Instance())->databaseQuery($sql);
        $result = (User::Instance())->databaseFetch($result);
        if($result) {
            return $result;
        } else {
            return false;
        }
    }
}