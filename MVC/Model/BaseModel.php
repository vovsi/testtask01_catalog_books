<?php

namespace MVC\Model {

    use MVC\View\ViewModel;

    class BaseModel
    {
        protected $viewModel;

        // создаем базовый класс и вспомогательный объект доступный для всех моделей
        public function __construct()
        {
            $this->viewModel = new ViewModel ();
            $this->commonViewData();
        }

        // устаналвиваем viewModel требуемые всеми представлениеями
        protected function commonViewData()
        {
            // например $this->viewModel->set("mainMenu",array("Home" => "/home", "Help" => "/help"));
        }
    }
}