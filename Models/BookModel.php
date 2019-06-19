<?php

namespace Models {

    use MVC\Model\BaseModel;

    class BookModel extends BaseModel
    {
        // Данные, передаваемые в представление index
        public function index($errors, $books)
        {
            $this->viewModel->set("pageTitle", "Books");
            $this->viewModel->set("errors", $errors);
            $this->viewModel->set("books", $books);
            return $this->viewModel;
        }

        public function details($book, $errors = null)
        {
            $this->viewModel->set("pageTitle", "Details Book");
            $this->viewModel->set("book", $book);
            $this->viewModel->set("errors", $errors);
            return $this->viewModel;
        }

        public function add($errors = null, $headings, $publishers, $authors)
        {
            $this->viewModel->set("pageTitle", "Add Book");
            $this->viewModel->set("headings", $headings);
            $this->viewModel->set("publishers", $publishers);
            $this->viewModel->set("authors", $authors);
            $this->viewModel->set("errors", $errors);
            return $this->viewModel;
        }

        public function remove($errors = null)
        {
            $this->viewModel->set("pageTitle", "Remove Book");
            $this->viewModel->set("errors", $errors);
            return $this->viewModel;
        }

        public function edit($errors = null, $book, $headings, $publishers, $authors)
        {
            $this->viewModel->set("pageTitle", "Edit Book");
            $this->viewModel->set("headings", $headings);
            $this->viewModel->set("publishers", $publishers);
            $this->viewModel->set("authors", $authors);
            $this->viewModel->set("book", $book);
            $this->viewModel->set("errors", $errors);
            return $this->viewModel;
        }
    }
}
