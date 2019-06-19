<?php

namespace MVC\Controllers {

    use MVC\View\View;

    abstract class BaseController
    {
        protected $action;
        protected $id = null;
        protected $model;
        protected $view;

        public function __construct(array $init)
        {
            $this->action = $init['action'];
            if (isset($init['id'])) {
                $this->id = $init['id'];
            }

            // устанавливаем объект представление
            $this->view = new View (get_class($this), $this->action);
        }

        // выполняем запрашиваемый метод
        public function executeAction()
        {
            return $this->{$this->action} ($this->id);
        }
    }
}
