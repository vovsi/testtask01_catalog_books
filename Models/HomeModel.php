<?php

namespace Models {

    use MVC\Model\BaseModel;

    class HomeModel extends BaseModel
    {
        // Данные, передаваемые в представление index
        public function index()
        {
            $this->viewModel->set("pageTitle", "Home");
            return $this->viewModel;
        }
    }
} 
