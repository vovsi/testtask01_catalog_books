<?php

namespace MVC {

    use MVC\Config\ControllerConfig;
    use MVC\Config\RouteConfig;
    use MVC\Controllers\BaseController;
    use MVC\Controllers\ErrorController;

    class App
    {
        private $controllerName;
        private $controllerClass;
        private $action;
        private $id;

        // разбираем URL запрос
        public function __construct()
        {
            $controller = filter_input(INPUT_GET, 'controller', FILTER_SANITIZE_STRING);
            $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
            $this->id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

            if (empty ($controller)) { // Контроллер по умолчанию
                $this->controllerName = RouteConfig::MAP ['defaults'] ['controller'];
            } else {
                $this->controllerName = strtolower($controller);
            }
            $this->controllerClass = ControllerConfig::makeClassName($this->controllerName);

            if (empty ($action)) {
                $this->action = RouteConfig::MAP ['defaults'] ['action'];
            } else {
                $this->action = $action;
            }
        }

        // фабричный метод который создает контроллер
        public function createController(): BaseController
        {
            // существует ли файл с контроллером ?
            if (!file_exists(ControllerConfig::getFile($this->controllerName))) {
                return new ErrorController ([
                    'action' => "badController",
                    'errorMessage' => "Файл контроллера {$this->controllerClass} не существует"
                ]);
            }
            // существует ли класс?
            if (!\class_exists($this->controllerClass, true)) {
                // ошибка нет класса контроллера
                return new ErrorController ([
                    'action' => "badController",
                    'errorMessage' => "Класса контроллера {$this->controllerClass} не существует"
                ]);
            }

            $parents = class_parents($this->controllerClass);
            // является ли этот класс наследником класса BaseController?
            if (!\in_array("MVC\\Controllers\\BaseController", $parents)) {
                // ошибка - нет класса контроллера унаследованного от BaseController
                return new ErrorController ([
                    'action' => "badController",
                    'errorMessage' => "Класс контроллера не является наследником класса BaseController"
                ]);
            }

            // содержит ли этот класс метод action?
            if (\method_exists($this->controllerClass, $this->action)) {
                return new $this->controllerClass ([
                    'action' => $this->action,
                    'id' => $this->id
                ]);
            } else { // ошибка - нет метода action
                return new ErrorController ([
                    'action' => "badController",
                    'errorMessage' => "Класс контроллера не реализует метод-действие: {$this->action}"
                ]);
            }
        }
    }
}
