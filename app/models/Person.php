<?php

namespace Tale\Wms\Model;

use Tale\App\ModelBase;
use Tale\Wms\Model\TimestampTrait;


class Person extends ModelBase {
    use TimestampTrait;

    public $firstName = 'string(128)';
    public $lastName = 'string(128)';

    public $loginName = 'string(64) required';
    public $passwordHash = 'text';

    public function setPassword( $password ) {

        $this->passwordHash = password_hash( $password, \PASSWORD_DEFAULT );

        return $this;
    }

    public function isPassword( $password ) {

        return password_verify( $password, $this->passwordHash );
    }
}