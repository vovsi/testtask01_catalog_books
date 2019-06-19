<?php

namespace MVC\Config {

    class ControllerConfig
    {
        const DIR = 'Controllers';
        const SUFFIX = 'Controller';
        const ERROR = 'error';
        const EXT = ".php";

        public static function getName($controllerClass)
        {
            if (\preg_match('/' . self::DIR . '\\\\(\w+?)' . self::SUFFIX . '/', $controllerClass, $match)) {
                return $match[1];
            }
        }

        public static function makeClassName($name)
        {
            return self::DIR . '\\' . ucfirst(strtolower($name)) . self::SUFFIX;
        }

        public static function getFile($name)
        {
            return str_replace("\\", "/", self::makeClassName($name)) . self::EXT;
        }
    }
}