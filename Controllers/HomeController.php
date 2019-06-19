<?php

namespace Controllers {

    use Models\HomeModel;
    use MVC\Controllers\BaseController;

    class HomeController extends BaseController
    {
        public function __construct($init)
        {
            parent::__construct($init);
            // создаем модель
            $this->model = new HomeModel ();
        }

        // действие по умолчанию
        protected function index()
        {
            $this->view->output($this->model->index());
        }
    }
} 
