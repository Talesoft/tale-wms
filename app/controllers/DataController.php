<?php

namespace Tale\Wms\Controller;

use Tale\App\ControllerBase;

class DataController extends ControllerBase
{

    public function indexAction()
    {

        return $this->redirect("data/database.{{format}}");
    }
}