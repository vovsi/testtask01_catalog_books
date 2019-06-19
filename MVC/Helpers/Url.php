<?php

namespace MVC\Helpers {

    class Url
    {
        // Сгенерировать абсолютную ссылку
        public static function to($controller = 'home', $action = 'index', $id = '')
        {
            $url = preg_replace('/[\\\\\\/]+/', '/', '/' . substr(__DIR__,
                    strlen($_SERVER['DOCUMENT_ROOT'])) . '/');
            $url = preg_replace('/\/MVC.+/m', '', $url);
            return $url . "/$controller/$action/$id";
        }
    }
}