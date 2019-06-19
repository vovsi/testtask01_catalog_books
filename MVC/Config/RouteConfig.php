<?php

namespace MVC\Config {

    class RouteConfig
    {
        const MAP = [
            'name' => "Default",
            'url' => "{controller}/{action}/{id}",
            'defaults' => [
                'controller' => "home",
                'action' => "index",
                'id' => null
            ]
        ];
    }
}