<?php

class Koneksi
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "routiingoop";

    public function getKoneksi()
    {
        $koneksi = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($koneksi->connect_error) {
            die("Koneksi gagal: " . $koneksi->connect_error);
        }
        return $koneksi;
    }
}

?>