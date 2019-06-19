<?php

namespace Models {

    use MVC\Model\BaseModel;

    class PublisherModel extends BaseModel
    {
        // Данные, передаваемые в представление index
        public function index($errors, $publishers)
        {
            $this->viewModel->set("pageTitle", "Publishers");
            $this->viewModel->set("errors", $errors);
            $this->viewModel->set("publishers", $publishers);
            return $this->viewModel;
        }

        public function add($errors = null)
        {
            $this->viewModel->set("pageTitle", "Add Publisher");
            $this->viewModel->set("errors", $errors);
            return $this->viewModel;
        }

        public function remove($errors = null)
        {
            $this->viewModel->set("pageTitle", "Remove Publisher");
            $this->viewModel->set("errors", $errors);
            return $this->viewModel;
        }

        public function edit($errors = null, $publisher)
        {
            $this->viewModel->set("pageTitle", "Edit Publisher");
            $this->viewModel->set("publisher", $publisher);
            $this->viewModel->set("errors", $errors);
            return $this->viewModel;
        }
    }
}
