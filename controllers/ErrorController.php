<?php

namespace Tale\Wms\Controller;

use Tale\App\ControllerBase;

class ErrorController extends ControllerBase {

    public function notFoundAction() {

        return $this->view(['error' => 'File not found', 'errorCode' => 404]);
    }
}