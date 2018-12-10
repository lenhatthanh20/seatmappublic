<?php

class Database {
    private $hostname = 'localhost';
    private $username = 'root';
    private $password = 'root';
    private $dbName = 'lenhatthanh2';

    private $conn = NULL;

    public function connect() {
        $this->conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->dbName);

        if($this->conn) { // connect successful
            return $this->conn;
        } else {
            echo 'connect fail!';
            exit();
        }
    }

    public function databaseQuery($sql) {
        $result = mysqli_query($this->connect(), $sql);
        if($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function databaseFetch($data) {
        $result = mysqli_fetch_all($data);
        return $result;
    }
}
