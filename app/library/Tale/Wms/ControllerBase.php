<?php

namespace Tale\Wms;

use Tale\App\ControllerBase as AppControllerBase;
use Tale\ClassLoader;

abstract class ControllerBase extends AppControllerBase
{

    private $_barcodeLoader;

    public function initBarcodeLoader()
    {

        //We need to add an extra loader for the broken (inconsistent)
        //.barcode naming in the barcode library
        $this->_barcodeLoader = new ClassLoader(
            $this->app->getOption('path').'/library/Barcode',
            'Barcode',
            '%s.barcode.php'
        );
        $this->_barcodeLoader->register();
    }

    public function initAuth()
    {

        $controller = $this->request->getController();
        $action = $this->request->getAction();

        if (!isset($this->session->personId) && $controller !== 'install' && !($controller === 'index' && in_array($action, ['login', 'logout'])))
            return $this->redirect('index/login');

        $id = intval($this->session->personId);

        $this->currentPerson = $this->db->persons->selectOne(['id' => $id]);

        //if (!$this->currentPerson)
            //return $this->redirect('index/login');
    }
}