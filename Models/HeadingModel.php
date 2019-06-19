<?php

namespace Models {

    use MVC\Model\BaseModel;

    class HeadingModel extends BaseModel
    {
        // Данные, передаваемые в представление index
        public function index($errors, $headings)
        {
            $this->viewModel->set("pageTitle", "Headings");
            $this->viewModel->set("errors", $errors);
            $this->viewModel->set("headings", $headings);
            return $this->viewModel;
        }

        public function add($errors = null, $headings)
        {
            $this->viewModel->set("pageTitle", "Add Heading");
            $this->viewModel->set("errors", $errors);
            $this->viewModel->set("headings", $headings);
            return $this->viewModel;
        }

        public function remove($errors = null)
        {
            $this->viewModel->set("pageTitle", "Remove Heading");
            $this->viewModel->set("errors", $errors);
            return $this->viewModel;
        }

        public function edit($errors = null, $headings, $heading)
        {
            $this->viewModel->set("pageTitle", "Edit Heading");
            $this->viewModel->set("errors", $errors);
            $this->viewModel->set("headings", $headings);
            $this->viewModel->set("heading", $heading);
            return $this->viewModel;
        }
    }
}
