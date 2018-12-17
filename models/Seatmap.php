<?php
require_once ('databaseConfig.php');

class Seatmap {

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
     * Add a seat map
     * @param $name
     * @param $type
     * @param $size
     * @param $path
     * @return bool
     */
    public function addSeatmap($name, $type, $size, $path) {
        $name = mysqli_real_escape_string((Seatmap::Instance())->connect(), $name);
        $type = mysqli_real_escape_string((Seatmap::Instance())->connect(), $type);
        $size = mysqli_real_escape_string((Seatmap::Instance())->connect(), $size);
        $path = mysqli_real_escape_string((Seatmap::Instance())->connect(), $path);
        $sql = "INSERT INTO seatmap (name, type, path, size) 
                            VALUES ('{$name}', '{$type}', '{$path}', '{$size}')";
        $result = (Seatmap::Instance())->databaseQuery($sql);
        if(isset($result)) {
            return true;
        }
        return false;
    }

    /**
     * List all seat maps
     * @return bool
     */
    public function listAllSeatmap() {
        $sql = "SELECT * FROM seatmap";
        $result = (Seatmap::Instance())->databaseQuery($sql);
        $result = (Seatmap::Instance())->databaseFetch($result);
        if($result) {
            return $result;
        }
        return false;
    }

    /**
     * Select a seat map
     * @param $id
     * @return bool
     */
    public function selectSeatmap($id) {
        $id = mysqli_real_escape_string((Seatmap::Instance())->connect(), $id);
        $sql = "SELECT * FROM seatmap WHERE id='{$id}'";
        $result = (Seatmap::Instance())->databaseQuery($sql);
        $result = (Seatmap::Instance())->databaseFetch($result);
        if($result) {
            return $result;
        }
        return false;
    }

    /**
     * Update a seat map without image path
     * @param $id
     * @param $name
     * @return bool
     */
    public function updateSeatmapWithoutPath($id, $name) {
        $name = mysqli_real_escape_string((Seatmap::Instance())->connect(), $name);
        $id = mysqli_real_escape_string((Seatmap::Instance())->connect(), $id);
        $sql = "UPDATE seatmap SET name='{$name}'
                WHERE id = '{$id}'";
        $result = (Seatmap::Instance())->databaseQuery($sql);
        if($result){
            return true;
        }
        return false;
    }

    /**
     * Update a seatmap with path
     * @param $id
     * @param $name
     * @param $path
     * @param $size
     * @param $type
     * @return bool
     */
    public function updateSeatmapWithPath($id, $name, $path, $size, $type) {
        $id = mysqli_real_escape_string((Seatmap::Instance())->connect(), $id);
        $name = mysqli_real_escape_string((Seatmap::Instance())->connect(), $name);
        $type = mysqli_real_escape_string((Seatmap::Instance())->connect(), $type);
        $size = mysqli_real_escape_string((Seatmap::Instance())->connect(), $size);
        $path = mysqli_real_escape_string((Seatmap::Instance())->connect(), $path);
        $sql = "UPDATE seatmap SET name='{$name}', path='{$path}', size='{$size}', type='{$type}'
                WHERE id = '{$id}'";
        $result = (Seatmap::Instance())->databaseQuery($sql);
        if($result){
            return true;
        }
        return false;
    }

    /**
     * Delete a seat map with id
     * @param $id
     * @return bool
     */
    public function deleteSeatmap($id) {
        $id = mysqli_real_escape_string((Seatmap::Instance())->connect(), $id);
        $sql = "DELETE FROM seatmap WHERE id = '{$id}'";
        $result = (Seatmap::Instance())->databaseQuery($sql);
        if($result) {
            return true;
        }
        return false;
    }

    /**
     * Check the id is exist or not
     * @param $id
     * @return bool
     */
    public function checkValidId($id) {
        $id = mysqli_real_escape_string((Seatmap::Instance())->connect(), $id);
        $sql = "SELECT IF( EXISTS(
                            SELECT id
                            FROM seatmap
                            WHERE id = '{$id}'
                            LIMIT 1), 'exist', 'notexist')";
        $result = (Seatmap::Instance())->databaseQuery($sql);
        $result = (Seatmap::Instance())->databaseFetch($result);
        if($result[0][0] === 'exist') {
            return true;
        }
        return false;
    }

    /**
     * Check a seat map name is exist or not
     * @param $name
     * @return bool
     */
    public function checkExistName($name) {
        $name = mysqli_real_escape_string((Seatmap::Instance())->connect(), $name);
        $sql = "SELECT IF( EXISTS(
                            SELECT name
                            FROM seatmap
                            WHERE name = '{$name}'
                            LIMIT 1), 'exist', 'notexist')";
        $result = (Seatmap::Instance())->databaseQuery($sql);
        $result = (Seatmap::Instance())->databaseFetch($result);
        if($result[0][0] === 'exist') {
            return true;
        }
        return false;
    }

    /**
     * Check seatmap name is exist or not except specific ID
     * @param $name
     * @param $id
     * @return bool
     */
    public function checkExistNameExceptId($name, $id) {
        $id = mysqli_real_escape_string((Seatmap::Instance())->connect(), $id);
        $name = mysqli_real_escape_string((Seatmap::Instance())->connect(), $name);
        $sql = "SELECT IF( EXISTS(
                            SELECT name
                            FROM seatmap
                            WHERE (name = '{$name}' AND id <> '{$id}')
                            LIMIT 1), 'exist', 'notexist')";
        $result = (Seatmap::Instance())->databaseQuery($sql);
        $result = (Seatmap::Instance())->databaseFetch($result);
        if($result[0][0] === 'exist') {
            return true;
        }
        return false;
    }
}