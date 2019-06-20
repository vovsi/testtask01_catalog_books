<?php

namespace MVC\Services {

    class UtilsService
    {
        // Регулярное выражение под ссылку на изображение
        const PATTERN_URL_IMAGE = '/^(https?|ftp)\:\/\/[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,3}(\/\S*)?(\w+.(jpg|png|gif|jpeg))/m';

        // Перейти по сгенерированной ссылке
        public static function redirect($controller = '', $action = '', $params = '')
        {
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $url = "Location: http://$host$uri/";
            if (!empty($controller)) {
                $url .= $controller . '/';
            }
            if (!empty($controller) && !empty($action)) {
                $url .= $action . '/';
            }
            if (!empty($params)) {
                $url .= $params;
            }
            header($url);
            exit;
        }
    }
}