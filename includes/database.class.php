<?php

class MySQLDatabase {

    private $connection;

    public function __construct() {
        $this->open_connection();
    }

    public function open_connection() {
        $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT, DB_SOCKET);
        if (mysqli_connect_errno())
            terminate_script("DB Connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
    }

    public function close_connection() {
        if (isset($this->connection)) {
            mysqli_close($this->connection);
            unset($this->connection);
        }
    }

    public function query($sql) {
        $result = mysqli_query($this->connection, $sql);
        $this->confirm_query($result, $sql);
        return $result;
    }

    private function confirm_query($result, $sql) {
        if (!$result) {
            terminate_script("Database query failed: " . $sql);
        }
    }

    public function escape_value($value) {
        return trim(mysqli_real_escape_string($this->connection, $value));
    }

    public function escape_array($array) {
        $escaped_array = array();
        foreach ($array as $key => $value) {
            $escaped_array[$this->escape_value($key)] = $this->escape_value($value);
        }
        return $escaped_array;
    }

    public function fetch_assoc($result_set) {
        return mysqli_fetch_assoc($result_set);
    }

    public function num_rows($result_set) {
        return mysqli_num_rows($result_set);
    }

    public function inserted_id() {
        return mysqli_insert_id($this->connection);
    }

    public function affected_rows() {
        return mysqli_affected_rows($this->connection);
    }
}

$db = new MySQLDatabase();