<?php

namespace Tale\Wms\Controller;

use Tale\App\ControllerBase;

class IndexController extends ControllerBase
{

    public function indexAction()
    {

        return $this->viewCached();
    }

    public function someDataAction()
    {


        return ['some' => 'random', 'data' => 42];
    }
}