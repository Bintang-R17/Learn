<?php

include_once 'model/database.php';
class BaseController{
    protected function view ($view, $data = []){
        foreach ($data as $key => $value) {
            $$key = $value;

        }

        include __DIR__ . '/../view/' . $view . '.php';
    }
}

?>