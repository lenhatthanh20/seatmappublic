<?php

CONST _HOST_NAME = 'localhost';
CONST _USERNAME = 'root';
CONST _PASSWORD = 'root';
CONST _DATABASE_NAME = 'lenhatthanh2';


class Database {
    private $hostname = _HOST_NAME;
    private $username = _USERNAME;
    private $password = _PASSWORD;
    private $dbName = _DATABASE_NAME;

    private $conn = NULL;

    /**
     * Connect to database
     * @return bool|mysqli|null
     */
    public function connect() {
        $this->conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->dbName);

        if($this->conn) {
            return $this->conn;
        }
        return false;
    }

    /**
     * Query to database
     * @param $sql
     * @return bool|mysqli_result
     */
    public function databaseQuery($sql) {
        $result = mysqli_query($this->connect(), $sql);
        if($result) {
            return $result;
        }
        return false;
    }

    /**
     * Fetch data
     * @param $data
     * @return array|null
     */
    public function databaseFetch($data) {
        $result = mysqli_fetch_all($data);
        return $result;
    }
}
