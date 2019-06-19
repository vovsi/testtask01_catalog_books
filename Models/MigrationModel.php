<?php

namespace Models {

    use MVC\Model\BaseModel;

    class MigrationModel extends BaseModel
    {
        // Данные, передаваемые в представление index
        public function index()
        {
            $this->viewModel->set("pageTitle", "Migrations");
            return $this->viewModel;
        }

        public function load($info = null)
        {
            $this->viewModel->set("pageTitle", "Load migrations");
            $this->viewModel->set("info", $info);
            return $this->viewModel;
        }
    }
}


