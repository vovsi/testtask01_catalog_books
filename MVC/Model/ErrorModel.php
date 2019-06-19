<?php

namespace MVC\Model {

    /*
     * Проект: MVC
     * Назначение: модель для контроллера ошибок
     */

    class ErrorModel extends BaseModel
    {

        public function __construct(string $errorMessage)
        {
            parent::__construct();

            $this->viewModel->set('errorMessage', $errorMessage);
        }

        // данные, передаваемы при проблемах с URL
        public function badURL()
        {
            $this->viewModel->set("pageTitle", "Error - Bad URL");
            return $this->viewModel;
        }

        public function badView()
        {
            $this->viewModel->set("pageTitle", "Error - Bad View");
            return $this->viewModel;
        }

        public function badTemplate()
        {
            $this->viewModel->set("pageTitle", "Error - Bad Template");
            return $this->viewModel;
        }

        public function badController()
        {
            $this->viewModel->set("pageTitle", "Error - Bad Controller");
            return $this->viewModel;
        }
    }
}