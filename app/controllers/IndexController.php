<?php

namespace Tale\Wms\Controller;

use Tale\Net\Http\Method;
use Tale\Wms\ControllerBase;

class IndexController extends ControllerBase {

	public function indexAction() {

        return $this->redirect('storage');
	}

	public function getLoginPersons()
	{

		foreach ($this->db->persons->where(['loginName!' => null])->selectArray() as $person)
			yield $person->loginName => $person->getFullName();
	}

	public function loginAction() {

        $this->addForm('login', [
			'loginName' => [
				'type' => 'select',
				'options' => iterator_to_array($this->getLoginPersons())
			],
            'password' => 'password'
		]);

        if ($this->hasFormData(Method::POST)) {

            $form = $this->getFilledForm('login', Method::POST);

            $person = $this->db->persons->where(['loginName' => $form->loginName->getValue()])->selectOne();

            if ($person) {

                $this->session->personId = $person->id;

                return $this->redirect('storage');
            }
        }

		$this->allPersons = $this->db->persons->where(['role' => ['admin', 'reader']])->select();

        $this->loginForm = $this->getForm('login');

        $errors = $this->loginForm->getErrors();
		return $this->view([
			'success' => !count($errors),
            'errors' => $errors
		]);
	}

	public function logoutAction() {

		unset($this->session->personId);

		return $this->redirect( 'index' );
	}

	public function scanCodeLoginAction() {

		$form = new Form( [
			'scanCode' => null
		] );

		if( $form->wasSent() ) {

			$form->scanCode->hasValue( 'Bitte scannen Sie einen validen Code ein' )
						   ->validate( $this->db->hasUserByScanCode( $form->scanCode->getString() ), 'Der übergebene Scan Code ist keinem Benutzer zugeordnet' );

			if( !$form->hasErrors() ) {

				$user = $this->db->getUserByScanCode( $form->scanCode->getString() );

				$_SESSION[ 'userId' ] = intval( $user->id );

				return [ 
					'success' => true,
					'user' => [
						'name' => $user->name,
						'scanCode' => $user->scan_code
					],
					'errors' => [] 
				];
			}
		}

		return [ 
			'success' => false, 
			'errors' => $form->getErrors( true ) 
		];
	}
}