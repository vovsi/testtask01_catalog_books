<?php

namespace MVC\Config {

    class ViewConfig
    {
        const DIR = 'Views';
        const EXT = '.php';
        const TEMPLATE = 'layouts/main' . self::EXT;
        const ERROR = [
            'subdir' => 'Error',
            'template' => 'BadTemplate' . self::EXT,
            'view' => 'BadView' . self::EXT
        ];

        public static function getFile($controllerClass, $action)
        {
            $controllerName = ControllerConfig::getName($controllerClass);
            return self::DIR . "/$controllerName/$action" . self::EXT;
        }

        public static function getErrorFile($typeError)
        {
            if (\array_key_exists($typeError, self::ERROR)) {
                return self::DIR . "/" . self::ERROR['subdir'] . '/' . self::ERROR[$typeError];
            }
        }

        public static function getTemplate($template)
        {
            if (empty($template)) {
                $file = self::DIR . "/" . self::TEMPLATE;
            } else {
                $file = self::DIR . "/" . $template . self::EXT;
            }
            return $file;
        }
    }
}