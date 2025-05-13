<?php

class Database {
    private $conn;

    public function __construct($dbHost, $dbUser, $dbPass, $oopmvc){
        $this -> conn = new mysqli($dbHost, $dbUser, $dbPass, $oopmvc);
        if ($this -> conn -> connect_error) {
            die("Connection failed: " . $this -> conn -> connect_error);
        }
    }
    public function getConnection(){
        return $this -> conn;
    }
}



?>