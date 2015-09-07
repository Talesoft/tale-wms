<?php

namespace Tale\Wms\Controller;

use Tale\Net\Http\Method;
use Tale\Wms\ControllerBase;

class IndexController extends ControllerBase {

    public function initRedirectReturn()
    {

        $this->addForm('redirectReturn', ['return' => 'text']);
        $form = $this->getFilledForm('redirectReturn', Method::GET);
        $return = $form->return->getValue();

        $this->returnUrl = $this->getUrl($return);
    }

	public function indexAction() {

        return $this->redirect('storage');
	}

	public function getLoginPersons()
	{

		foreach ($this->db->persons->selectArray(['loginName!' => null]) as $person)
			yield $person->loginName => $person->firstName;
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

            $form = $this->getFilledForm(Method::POST);

            var_dump($form);
        }

        $this->loginForm = $this->getForm('login');

        $errors = $this->loginForm->getErrors();
		return [
			'success' => !count($errors),
            'errors' => $errors
		];
	}

	public function logoutAction() {

		unset( $_SESSION[ 'userId' ] );

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