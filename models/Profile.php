<?php
require_once ('databaseConfig.php');

$database = new Database();
$link = $database->connect();

class Profile {
    /**
     * @method: Add one profile in database
     * @param: $username, $email, $picture
     * @return: bool. Return true if successful, false if failure.
     */
    public function addProfile($username, $email, $picture) {
        $username = mysqli_real_escape_string($GLOBALS['link'], $username);
        $picture = mysqli_real_escape_string($GLOBALS['link'], $picture);

        $sql = "INSERT INTO profile (name, email, picture)
                        VALUES ('{$username}', '{$email}', '{$picture}')";
        $result = $GLOBALS['database']->databaseQuery($sql);
        if(isset($result)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @method: List all profile in database
     * @param: $username, $email, $picture
     * @return: object|bool. Return object if successful, false if failure.
     */
    public function listAllProfile() {
        $sql = "SELECT * FROM profile";
        $result = $GLOBALS['database']->databaseQuery($sql);
        $result = $GLOBALS['database']->databaseFetch($result);
        if($result) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * @method: Select a profile
     * @param: $id
     * @return: object|bool. Return object if successful, false if failure.
     */
    public function selectProfile($id) {
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);

        $isIdValid = $this->checkValidId($id);
        if ($isIdValid !== true){
            return false;
        } else {
            $sql = "SELECT * FROM profile WHERE id='{$id}'";
            $result = $GLOBALS['database']->databaseQuery($sql);
            $result = $GLOBALS['database']->databaseFetch($result);
            if($result) {
                return $result;
            } else {
                return false;
            }
        }
    }

    /**
     * @method: Update a profile
     * @param: $id, $username, $email, $picture
     * @return: bool. Return true if successful, false if failure.
     */
    public function updateProfile($id, $username, $email, $picture) {
        $username = mysqli_real_escape_string($GLOBALS['link'], $username);
        $picture = mysqli_real_escape_string($GLOBALS['link'], $picture);
        $email = mysqli_real_escape_string($GLOBALS['link'], $email);
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);
        $isIdValid = $this->checkValidId($id);
        if ($isIdValid !== true){
            return false;
        }

        $sql = "UPDATE profile SET name='{$username}', email='{$email}', picture='{$picture}'
                WHERE id = '{$id}'";

        $result = $GLOBALS['database']->databaseQuery($sql);

        if($result){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @method: Update a profile when deleted out of seatmap
     * @param: $id
     * @return: bool. Return true if successful, false if failure.
     */
    public function updateProfileWhenRemoveSeatmap($id) {
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);
        $isIdValid = $this->checkValidId($id);
        if ($isIdValid !== true){
            return false;
        }
        $sql = "UPDATE profile SET position=NULL, seatmap_id=NULL
                WHERE id = '{$id}'";
        $result = $GLOBALS['database']->databaseQuery($sql);
        if($result){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @method: Delete a profile
     * @param: $id
     * @return: bool. Return true if successful, false if failure.
     */
    public function deleteProfile($id) {
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);
        $isIdValid = $this->checkValidId($id);
        if ($isIdValid !== true){
            return false;
        } else {
            $sql = "DELETE FROM profile WHERE id = '{$id}'";
            $result = $GLOBALS['database']->databaseQuery($sql);
            if($result) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @method: Check id is valid or not
     * @param: $id
     * @return: bool. Return true if id is exist, false if id is not exist.
     */
    public function checkValidId($id) {
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);
        $sql = "SELECT IF( EXISTS(
                            SELECT id
                            FROM profile
                            WHERE id = '{$id}'
                            LIMIT 1), 'exist', 'notexist')";
        $result = $GLOBALS['database']->databaseQuery($sql);
        $result = $GLOBALS['database']->databaseFetch($result);
        if($result[0][0] === 'exist') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @method: Check email is exist or not except specific ID
     * @param: $email
     * @return: bool. Return true if email is exist, false if email is not exist.
     */
    public function checkExistEmailExceptId($email, $id) {
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);
        $email = mysqli_real_escape_string($GLOBALS['link'], $email);
        $sql = "SELECT IF( EXISTS(
                            SELECT email
                            FROM profile
                            WHERE (email = '{$email}' AND id <> '{$id}')
                            LIMIT 1), 'exist', 'notexist')";
        $result = $GLOBALS['database']->databaseQuery($sql);
        $result = $GLOBALS['database']->databaseFetch($result);
        if($result[0][0] === 'exist') {
            return true;
        } else {
            return false;
        }
    }

    public function checkExistUsername($username) {
        $username = mysqli_real_escape_string($GLOBALS['link'], $username);
        $sql = "SELECT IF( EXISTS(
                            SELECT username
                            FROM user
                            WHERE username = '{$username}'
                            LIMIT 1), 'exist', 'notexist')";
        $result = $GLOBALS['database']->databaseQuery($sql);
        $result = $GLOBALS['database']->databaseFetch($result);
        if($result[0][0] === 'notexist') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @method: Check email is exist or not
     * @param: $email
     * @return: bool. Return true if email is exist, false if email is not exist.
     */
    public function checkExistEmail($email) {
        $email = mysqli_real_escape_string($GLOBALS['link'], $email);
        $sql = "SELECT IF( EXISTS(
                            SELECT email
                            FROM profile
                            WHERE email = '{$email}'
                            LIMIT 1), 'exist', 'notexist')";
        $result = $GLOBALS['database']->databaseQuery($sql);
        $result = $GLOBALS['database']->databaseFetch($result);
        if($result[0][0] === 'exist') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @method: Update position and seatmap_id
     * @param: $id, $position, $seatmap_id
     * @return: bool.
     *      Return true if success, false if failure.
     */
    public function updateProfileToSeatmap($id, $position, $seatmap_id) {
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);
        $position = mysqli_real_escape_string($GLOBALS['link'], $position);
        $seatmap_id = mysqli_real_escape_string($GLOBALS['link'], $seatmap_id);

        $sql = "UPDATE profile SET position='{$position}', seatmap_id='{$seatmap_id}'
                WHERE id = '{$id}'";
        $result = $GLOBALS['database']->databaseQuery($sql);
        if($result){
            return true;
        } else {
            return false;
        }
    }
}
