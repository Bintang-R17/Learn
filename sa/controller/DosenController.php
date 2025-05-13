<?php

include __DIR__ . '/../model/dosen.php';

class DosenController {
    private $model;

    public function __construct($db) {
        $this->model = new Dosen($db);
    }

    public function addDosen(){
        include __DIR__ . '/../view/addDosen.php';
    }

    public function addDosenProcess(){
        if (isset($_POST['nama_lengkap'], $_POST['mata_kuliah'], $_POST['nip'])){
            $result = $this->model->addDosen($_POST['nama_lengkap'], $_POST['mata_kuliah'], $_POST['nip']);
            if ($result > 0){
                header("Location: index.php?action=listDosen&status=success");
                exit;
            }else{
                echo "Data gagal ditambahkan";
            }
        }else{
            echo "Data tidak lengkap";
        }
    }
    
    public function listDosen(){
        $dosen = $this->model->getDosen();
        include __DIR__ . '/../view/listDosen.php';
    }

    public function editDosen(){
        if (isset($_GET['id'])){
            $id = $_GET['id'];
            $dsn = $this->model->getDosenById($id);
            include __DIR__ . '/../view/editDosen.php';
        }
    }

    public function updateDosenProcess(){
        if (isset($_POST['id'], $_POST['nama_lengkap'], $_POST['mata_kuliah'], $_POST['nip'])){
            $result = $this->model->updateDosen($_POST['id'], $_POST['nama_lengkap'], $_POST['mata_kuliah'], $_POST['nip']);
            if ($result > 0){
                header("Location: index.php?action=listDosen&status=updated");
                exit;
            }
        }
    }

    public function deleteDosen(){
        if (isset($_GET['id'])){
            $result = $this->model->deleteDosen($_GET['id']);
            if ($result > 0 ){
                header("Location: index.php?action=listDosen&status=deleted");
                exit;
            }
        }
    }

}

?>