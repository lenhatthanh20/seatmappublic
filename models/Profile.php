<?php
require_once ('databaseConfig.php');

class Profile {

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
     * @method: Add one profile in database
     * @param: $username, $email, $picture
     * @return: bool. Return true if successful, false if failure.
     */
    public function addProfile($username, $email, $picture) {
        $username = mysqli_real_escape_string((Profile::Instance())->connect(), $username);
        $picture = mysqli_real_escape_string((Profile::Instance())->connect(), $picture);
        $email = mysqli_real_escape_string((Profile::Instance())->connect(), $email);

        $sql = "INSERT INTO profile (name, email, picture)
                        VALUES ('{$username}', '{$email}', '{$picture}')";
        $result = (Profile::Instance())->databaseQuery($sql);
        if(isset($result)) {
            return true;
        }
        return false;
    }

    /**
     * @method: List all profile in database
     * @param: $username, $email, $picture
     * @return: object|bool. Return object if successful, false if failure.
     */
    public function listAllProfile() {
        $sql = "SELECT * FROM profile";
        $result = (Profile::Instance())->databaseQuery($sql);
        $result = (Profile::Instance())->databaseFetch($result);
        if($result) {
            return $result;
        }
        return false;
    }

    /**
     * @method: Select a profile
     * @param: $id
     * @return: object|bool. Return object if successful, false if failure.
     */
    public function selectProfile($id) {
        $id = mysqli_real_escape_string((Profile::Instance())->connect(), $id);

        $isIdValid = $this->checkValidId($id);
        if ($isIdValid !== true){
            return false;
        } else {
            $sql = "SELECT * FROM profile WHERE id='{$id}'";
            $result = (Profile::Instance())->databaseQuery($sql);
            $result = (Profile::Instance())->databaseFetch($result);
            if($result) {
                return $result;
            }
            return false;
        }
    }

    /**
     * @method: Update a profile
     * @param: $id, $username, $email, $picture
     * @return: bool. Return true if successful, false if failure.
     */
    public function updateProfile($id, $username, $email, $picture) {
        $username = mysqli_real_escape_string((Profile::Instance())->connect(), $username);
        $picture = mysqli_real_escape_string((Profile::Instance())->connect(), $picture);
        $email = mysqli_real_escape_string((Profile::Instance())->connect(), $email);
        $id = mysqli_real_escape_string((Profile::Instance())->connect(), $id);
        $isIdValid = $this->checkValidId($id);

        if ($isIdValid !== true){
            return false;
        }

        $sql = "UPDATE profile SET name='{$username}', email='{$email}', picture='{$picture}'
                WHERE id = '{$id}'";

        $result = (Profile::Instance())->databaseQuery($sql);

        if($result){
            return true;
        }
        return false;
    }

    /**
     * @method: Update a profile when deleted out of seatmap
     * @param: $id
     * @return: bool. Return true if successful, false if failure.
     */
    public function updateProfileWhenRemoveSeatmap($id) {
        $id = mysqli_real_escape_string((Profile::Instance())->connect(), $id);
        $isIdValid = $this->checkValidId($id);
        if ($isIdValid !== true){
            return false;
        }
        $sql = "UPDATE profile SET position=NULL, seatmap_id=NULL
                WHERE id = '{$id}'";
        $result = (Profile::Instance())->databaseQuery($sql);
        if($result){
            return true;
        }
        return false;
    }

    /**
     * @method: Delete a profile
     * @param: $id
     * @return: bool. Return true if successful, false if failure.
     */
    public function deleteProfile($id) {
        $id = mysqli_real_escape_string((Profile::Instance())->connect(), $id);
        $isIdValid = $this->checkValidId($id);
        if ($isIdValid !== true){
            return false;
        } else {
            $sql = "DELETE FROM profile WHERE id = '{$id}'";
            $result = (Profile::Instance())->databaseQuery($sql);
            if($result) {
                return true;
            }
            return false;
        }
    }

    /**
     * @method: Check id is valid or not
     * @param: $id
     * @return: bool. Return true if id is exist, false if id is not exist.
     */
    public function checkValidId($id) {
        $id = mysqli_real_escape_string((Profile::Instance())->connect(), $id);
        $sql = "SELECT IF( EXISTS(
                            SELECT id
                            FROM profile
                            WHERE id = '{$id}'
                            LIMIT 1), 'exist', 'notexist')";
        $result = (Profile::Instance())->databaseQuery($sql);
        $result = (Profile::Instance())->databaseFetch($result);
        if($result[0][0] === 'exist') {
            return true;
        }
        return false;
    }

    /**
     * @method: Check email is exist or not except specific ID
     * @param: $email
     * @return: bool. Return true if email is exist, false if email is not exist.
     */
    public function checkExistEmailExceptId($email, $id) {
        $id = mysqli_real_escape_string((Profile::Instance())->connect(), $id);
        $email = mysqli_real_escape_string((Profile::Instance())->connect(), $email);
        $sql = "SELECT IF( EXISTS(
                            SELECT email
                            FROM profile
                            WHERE (email = '{$email}' AND id <> '{$id}')
                            LIMIT 1), 'exist', 'notexist')";
        $result = (Profile::Instance())->databaseQuery($sql);
        $result = (Profile::Instance())->databaseFetch($result);
        if($result[0][0] === 'exist') {
            return true;
        }
        return false;
    }

    /**
     * Check Username is exist or not
     * @param $username
     * @return bool
     */
    public function checkExistUsername($username) {
        $username = mysqli_real_escape_string((Profile::Instance())->connect(), $username);
        $sql = "SELECT IF( EXISTS(
                            SELECT username
                            FROM user
                            WHERE username = '{$username}'
                            LIMIT 1), 'exist', 'notexist')";
        $result = (Profile::Instance())->databaseQuery($sql);
        $result = (Profile::Instance())->databaseFetch($result);
        if($result[0][0] === 'notexist') {
            return true;
        }
        return false;
    }

    /**
     * @method: Check email is exist or not
     * @param: $email
     * @return: bool. Return true if email is exist, false if email is not exist.
     */
    public function checkExistEmail($email) {
        $email = mysqli_real_escape_string((Profile::Instance())->connect(), $email);
        $sql = "SELECT IF( EXISTS(
                            SELECT email
                            FROM profile
                            WHERE email = '{$email}'
                            LIMIT 1), 'exist', 'notexist')";
        $result = (Profile::Instance())->databaseQuery($sql);
        $result = (Profile::Instance())->databaseFetch($result);
        if($result[0][0] === 'exist') {
            return true;
        }
        return false;
    }

    /**
     * @method: Update position and seatmap_id
     * @param: $id, $position, $seatmap_id
     * @return: bool.
     *      Return true if success, false if failure.
     */
    public function updateProfileToSeatmap($id, $position, $seatmap_id) {
        $id = mysqli_real_escape_string((Profile::Instance())->connect(), $id);
        $position = mysqli_real_escape_string((Profile::Instance())->connect(), $position);
        $seatmap_id = mysqli_real_escape_string((Profile::Instance())->connect(), $seatmap_id);

        $sql = "UPDATE profile SET position='{$position}', seatmap_id='{$seatmap_id}'
                WHERE id = '{$id}'";
        $result = (Profile::Instance())->databaseQuery($sql);
        if($result){
            return true;
        }
        return false;
    }
}
