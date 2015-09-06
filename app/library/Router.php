<?php

namespace Tale\Wms;

class Router {

    public static function routeData(array $routeData)
    {

        if (!empty($routeData['controller']))
            $routeData['controller'] = 'data.'.$routeData['controller'];

        return $routeData;
    }
}