<?php
require_once ('databaseConfig.php');

$database = new Database();
$link = $database->connect();

class Seatmap {

    /**
     * @method: Add a seatmap
     * @param: $name, $type, $size, $path
     * @return: bool. Return true if successful, false if failure.
     */
    public function addSeatmap($name, $type, $size, $path) {
        $name = mysqli_real_escape_string($GLOBALS['link'], $name);
        $type = mysqli_real_escape_string($GLOBALS['link'], $type);
        $size = mysqli_real_escape_string($GLOBALS['link'], $size);
        $path = mysqli_real_escape_string($GLOBALS['link'], $path);
        $sql = "INSERT INTO seatmap (name, type, path, size) 
                            VALUES ('{$name}', '{$type}', '{$path}', '{$size}')";
        $result = $GLOBALS['database']->databaseQuery($sql);
        if(isset($result)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @method: List all seatmaps
     * @param: none
     * @return: object|bool. Return object if successful, false if failure.
     */
    public function listAllSeatmap() {
        $sql = "SELECT * FROM seatmap";
        $result = $GLOBALS['database']->databaseQuery($sql);
        $result = $GLOBALS['database']->databaseFetch($result);
        if($result) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * @method: Select a seatmap
     * @param: $id
     * @return: object|bool. Return object if successful, false if failure.
     */
    public function selectSeatmap($id) {
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);
        $sql = "SELECT * FROM seatmap WHERE id='{$id}'";
        $result = $GLOBALS['database']->databaseQuery($sql);
        $result = $GLOBALS['database']->databaseFetch($result);
        if($result) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * @method: Update a seatmap without image path
     * @param: $id, $name
     * @return: bool. Return true if successful, false if failure.
     */
    public function updateSeatmapWithoutPath($id, $name) {
        $name = mysqli_real_escape_string($GLOBALS['link'], $name);
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);
        $sql = "UPDATE seatmap SET name='{$name}'
                WHERE id = '{$id}'";
        $result = $GLOBALS['database']->databaseQuery($sql);
        if($result){
            return true;
        } else {
            return false;
        }
    }
    /**
     * @method: Update a seatmap with path
     * @param: $id, $name, $path
     * @return: bool. Return true if successful, false if failure.
     */
    public function updateSeatmapWithPath($id, $name, $path, $size, $type) {
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);
        $name = mysqli_real_escape_string($GLOBALS['link'], $name);
        $type = mysqli_real_escape_string($GLOBALS['link'], $type);
        $size = mysqli_real_escape_string($GLOBALS['link'], $size);
        $path = mysqli_real_escape_string($GLOBALS['link'], $path);
        $sql = "UPDATE seatmap SET name='{$name}', path='{$path}', size='{$size}', type='{$type}'
                WHERE id = '{$id}'";
        $result = $GLOBALS['database']->databaseQuery($sql);
        if($result){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @method: Delete a seatmap with id
     * @param: $id
     * @return: Bool. Return true if successful, false if failure.
     */
    public function deleteSeatmap($id) {
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);
        $sql = "DELETE FROM seatmap WHERE id = '{$id}'";
        $result = $GLOBALS['database']->databaseQuery($sql);
        if($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @method: Check the id is exist or not
     * @param: $id
     * @return: Bool. Return true if exist, false if not.
     */
    public function checkValidId($id) {
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);
        $sql = "SELECT IF( EXISTS(
                            SELECT id
                            FROM seatmap
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
     * @method: Check a seatmap name is exist or not
     * @param: $name
     * @return: bool. Return true if the seatmap name is exist, false if not exist.
     */
    public function checkExistName($name) {
        $name = mysqli_real_escape_string($GLOBALS['link'], $name);
        $sql = "SELECT IF( EXISTS(
                            SELECT name
                            FROM seatmap
                            WHERE name = '{$name}'
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
     * @method: Check seatmap name is exist or not except specific ID
     * @param: $name, $id
     * @return: bool. Return true if email is exist, false if email is not exist.
     */
    public function checkExistNameExceptId($name, $id) {
        $id = mysqli_real_escape_string($GLOBALS['link'], $id);
        $name = mysqli_real_escape_string($GLOBALS['link'], $name);
        $sql = "SELECT IF( EXISTS(
                            SELECT name
                            FROM seatmap
                            WHERE (name = '{$name}' AND id <> '{$id}')
                            LIMIT 1), 'exist', 'notexist')";
        $result = $GLOBALS['database']->databaseQuery($sql);
        $result = $GLOBALS['database']->databaseFetch($result);
        if($result[0][0] === 'exist') {
            return true;
        } else {
            return false;
        }
    }
}