<?php

class Dosen {
    private $conn;

    public function __construct($db){
        $this->conn = $db->getConnection();
    }

    public function getDosen(){
        $sql = "SELECT * FROM dosen";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function addDosen($nama_lengkap, $mata_kuliah, $nip ){
        $stmt = $this->conn->prepare("INSERT INTO dosen (nama_lengkap, mata_kuliah, nip) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nama_lengkap, $mata_kuliah, $nip);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function getDosenById($id){
        $stmt = $this->conn->prepare("SELECT * FROM dosen WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function updateDosen($id, $nama_lengkap, $mata_kuliah, $nip){
        $stmt = $this->conn->prepare("UPDATE dosen SET nama_lengkap = ?, mata_kuliah = ?, nip = ? WHERE id = ?");
        $stmt->bind_param("ssii", $nama_lengkap, $mata_kuliah, $nip, $id);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function deleteDosen($id){
        $stmt = $this->conn->prepare("DELETE FROM dosen WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows;
    }
}

?>