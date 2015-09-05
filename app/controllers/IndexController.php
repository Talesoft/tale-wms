<?php

namespace Tale\Wms\Controller;

use Tale\App\ControllerBase;

class IndexController extends ControllerBase
{

    public function indexAction()
    {

        var_dump(iterator_to_array($this->db->getModelFields('persons')));

        return $this->viewCached();
    }

    public function someDataAction()
    {


        return ['some' => 'random', 'data' => 42];
    }
}