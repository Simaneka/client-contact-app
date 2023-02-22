<?php

class Database
{

    // DB Params
    public $conn;

    public function __construct()
    {

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "phonebook";

        // Create connection
        $this->conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}
