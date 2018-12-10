<?php
require_once ('databaseConfig.php');

$database = new Database();
$link = $database->connect();

class User {

    /**
     * @method: Add user
     * @param: $username, $password
     * @return: bool. Return true if add user successfully, false if failure.
     */
    public function addUser($username, $password) {
        $username = mysqli_real_escape_string($GLOBALS['link'], $username);
        $sql = "INSERT INTO User (username, password) VALUES ('{$username}', '{$password}')";
        $result = $GLOBALS['database']->databaseQuery($sql);
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
        $username = mysqli_real_escape_string($GLOBALS['link'], $username);
        $sql = "SELECT IF( EXISTS(
                            SELECT username
                            FROM user
                            WHERE username = '{$username}'
                            LIMIT 1), 'exist', 'notExist')";
        $result = $GLOBALS['database']->databaseQuery($sql);
        $result = $GLOBALS['database']->databaseFetch($result);
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
        $username = mysqli_real_escape_string($GLOBALS['link'], $username);
        $sql = "SELECT * FROM user WHERE username = '{$username}'";
        $result = $GLOBALS['database']->databaseQuery($sql);
        $result = $GLOBALS['database']->databaseFetch($result);
        if($result) {
            return $result;
        } else {
            return false;
        }
    }
}