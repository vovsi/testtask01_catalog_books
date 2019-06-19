<?php

namespace Models {

    use MVC\Model\BaseModel;

    class AuthorModel extends BaseModel
    {
        // Данные, передаваемые в представление index
        public function index($errors, $authors)
        {
            $this->viewModel->set("pageTitle", "Authors");
            $this->viewModel->set("errors", $errors);
            $this->viewModel->set("authors", $authors);
            return $this->viewModel;
        }

        public function add($errors = null)
        {
            $this->viewModel->set("pageTitle", "Add Author");
            $this->viewModel->set("errors", $errors);
            return $this->viewModel;
        }

        public function remove($errors = null)
        {
            $this->viewModel->set("pageTitle", "Remove Author");
            $this->viewModel->set("errors", $errors);
            return $this->viewModel;
        }

        public function edit($errors = null, $author)
        {
            $this->viewModel->set("pageTitle", "Edit Author");
            $this->viewModel->set("author", $author);
            $this->viewModel->set("errors", $errors);
            return $this->viewModel;
        }
    }
}
