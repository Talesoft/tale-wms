<?php

namespace Tale\Wms;

use Tale\App\ControllerBase as AppControllerBase;
use Tale\ClassLoader;
use Tale\Net\Http\Method;

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

    public function initRedirectReturn()
    {

        $this->addForm('redirectReturn', ['returnTo' => 'text']);
        $form = $this->getFilledForm('redirectReturn', Method::GET);
        $return = $form->returnTo->getValue();

        $this->returnUrl = $return;
    }

    public function redirectReturn($path)
    {

        //TODO: Fuck this!
        $req = $this->request;
        $controller = $req->getController();
        $action = $req->getAction();
        $id = $req->getArgs();
        $format = $req->getFormat();
        $url = $controller;

        if ($action)
            $url .= "/$action";

        if ($id['id'])
            $url .= "/{$id['id']}";

        if ($format)
            $url .= ".$format";

        return $this->redirect($path, [
           'returnTo' => $url
        ]);
    }

    public function redirectWithReturn($path)
    {

        if (!empty($this->returnUrl))
            return $this->redirect($this->returnUrl);

        return $this->redirect($path);
    }

    public function initJsOptions()
    {

        $this->jsOptions = json_encode([
            'baseUrl' => $this->app->getOption('url')
        ]);
    }

    public function initAuth()
    {

        $controller = $this->request->getController();
        $action = $this->request->getAction();

        $shouldAuth = !isset($this->session->personId) && $controller !== 'install' && !($controller === 'index' && in_array($action, ['login', 'logout']));

        if ($shouldAuth)
            return $this->redirectReturn('index/login');

        $id = intval($this->session->personId);

        $this->currentPerson = $this->db->persons->selectOne(['id' => $id]);

        if (!$this->currentPerson && $shouldAuth)
            return $this->redirectReturn('index/login');
    }
}