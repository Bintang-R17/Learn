<?php 

class User {
    public $nama;
    public $password;

    public function __construct($nama, $password) {
        $this->nama = $nama;
        $this->password = $password;
    }
}

?>