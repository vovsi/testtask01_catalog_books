<?php

ini_set('error_reporing', E_ALL);

spl_autoload_register(function ($className) {
    require_once $className . '.php';
});

$app = new MVC\App();                        //создаем загрузчик
$controller = $app->createController();        //создаем запрашиваемый контроллер на основе запроса 'controller' в URL
$controller->executeAction();                //выполняем запрос на основе действия 'action' в URL. Методы контроллера
// возвращает представление View.
