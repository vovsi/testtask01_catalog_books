<?php

/*
 * Проект: MVC
 * Назначение: контроллер для обработки ошибок.
 */

namespace MVC\Controllers {

    use MVC\Model\ErrorModel;

    class ErrorController extends BaseController
    {
        private $errorMessage = null;

        public function __construct(array $init)
        {
            parent::__construct($init);
            if (isset($init['errorMessage'])) {
                $this->errorMessage = $init['errorMessage'];
            }

            // создаем модель
            $this->model = new ErrorModel ($this->errorMessage);
        }

        public function __call($actionName, $args)
        {
            if (method_exists($this->model, $actionName)) {
                $this->view->output($this->model->badURL());
            }
        }

// 		// плохой URL запрос
// 		protected function badURL() {
// 			$this->view->output ( $this->model->badURL () );
// 		}

// 		// не найдена вюха
// 		protected function badView() {
// 			$this->view->output ( $this->model->badView () );
// 		}

// 		// не найден шаблон
// 		protected function badTemplate() {
// 			$this->view->output ( $this->model->badTemplate () );
// 		}

// 		// не найдено действие в контроллере
// 		protected function badController() {
// 			$this->view->output ( $this->model->badController() );
// 		}
    }
}
