<?php

namespace Tale\Wms\Controller;

use Tale\App\ControllerBase;

class IndexController extends ControllerBase {

    public function indexAction() {

        return $this->view();
    }
}